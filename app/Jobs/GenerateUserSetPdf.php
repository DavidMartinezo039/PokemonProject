<?php

namespace App\Jobs;

use App\Models\UserSet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class GenerateUserSetPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userSet;

    /**
     * Crear una nueva instancia del Job.
     */
    public function __construct(UserSet $userSet)
    {
        $this->userSet = $userSet;
    }

    /**
     * Ejecutar el Job.
     */
    public function handle()
    {
        Log::info("Generando PDF para el UserSet ID: {$this->userSet->id}");

        // Asegurarnos de que el UserSet tenga las relaciones necesarias
        $userSet = UserSet::with('cards')->find($this->userSet->id);

        if (!$userSet) {
            Log::error("UserSet ID: {$this->userSet->id} no encontrado.");
            return;
        }

        $imageDirectory = "storage/pdf_images/{$userSet->id}/";

        // Verificamos que las imágenes necesarias estén disponibles
        $expectedImages = [];

        if ($userSet->image) {
            $expectedImages[] = "set_image_" . pathinfo($userSet->image, PATHINFO_FILENAME) . ".jpg";
        }

        foreach ($userSet->cards as $index => $card) {
            if (isset($card->images['small'])) {
                $expectedImages[] = "card_{$index}_{$card->id}.jpg";
            }
        }

        // Verificamos las imágenes existentes
        $existingImages = File::exists($imageDirectory) ? File::files($imageDirectory) : [];
        $existingImages = array_map(fn($file) => $file->getFilename(), $existingImages);

        // Eliminamos imágenes que ya no están en el set
        foreach ($existingImages as $file) {
            if (!in_array($file, $expectedImages)) {
                File::delete("{$imageDirectory}{$file}");
            }
        }

        // Identificamos las imágenes faltantes
        $missingImages = array_diff($expectedImages, $existingImages);

        // Si faltan imágenes, lanzamos el job de descarga antes de generar el PDF
        if (!empty($missingImages)) {
            DownloadImagesForPDF::dispatchSync($userSet);
        }

        // Generar el PDF
        $pdf = Pdf::loadView('user-sets.pdf_view', compact('userSet', 'imageDirectory'));

        // Definir la ruta donde se guardará el PDF
        $pdfPath = "pdfs/user_sets/{$userSet->id}.pdf";
        Storage::disk('public')->put($pdfPath, $pdf->output());

        Log::info("PDF generado y guardado en: {$pdfPath}");
    }
}
