<?php

namespace App\Http\Controllers;

use App\Jobs\DownloadImagesForPDF;
use App\Models\UserSet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PDFController extends Controller
{
    public function generatePDF($userSetId)
    {
        $userSet = UserSet::with('cards')->find($userSetId);

        if (!$userSet) {
            return response('User Set not found', 404);
        }

        $imageDirectory = "storage/pdf_images/{$userSet->id}/";


        //Guardamos todas las imagenes en un array
        $expectedImages = [];

        if ($userSet->image) {
            $expectedImages[] = "set_image_" . pathinfo($userSet->image, PATHINFO_FILENAME) . ".jpg";
        }

        foreach ($userSet->cards as $index => $card) {
            if (isset($card->images['small'])) {
                $expectedImages[] = "card_{$index}_{$card->id}.jpg";
            }
        }

        // Verificamos si el directorio existe y obtenemos las imágenes actuales en él
        $existingImages = File::exists($imageDirectory) ? File::files($imageDirectory) : [];
        $existingImages = array_map(fn($file) => $file->getFilename(), $existingImages);

        //Eliminamos imagenes que ya no se encuentran en el set
        foreach ($existingImages as $file) {
            if (!in_array($file, $expectedImages)) {
                File::delete("{$imageDirectory}{$file}");
            }
        }

        //Comprobamos las imagenes que faltan
        $missingImages = array_diff($expectedImages, $existingImages);

        // Si hay imágenes faltantes, las descargamos de inmediato (sin esperar)
        if (!empty($missingImages)) {
            (new DownloadImagesForPDF($userSet))->handle();
        }

        $pdf = Pdf::loadView('user-sets.pdf_view', compact('userSet', 'imageDirectory'));

        return $pdf->download("{$userSet->name}.pdf");
    }
}
