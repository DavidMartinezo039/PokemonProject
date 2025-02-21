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

        $userSet = UserSet::with('cards')->find($this->userSet->id);

        $imageDirectory = "storage/cards/";

        $pdf = Pdf::loadView('user-sets.pdf_view', compact('userSet', 'imageDirectory'));

        $pdfPath = "pdfs/user_sets/{$userSet->id}.pdf";
        Storage::disk('public')->put($pdfPath, $pdf->output());

        Log::info("PDF generado y guardado en: {$pdfPath}");
    }
}
