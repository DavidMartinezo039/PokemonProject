<?php

namespace App\Http\Controllers;

use App\Jobs\DownloadImagesForPDF;
use App\Models\UserSet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDF($userSetId)
    {
        $userSet = UserSet::with('cards')->find($userSetId);

        if (!$userSet) {
            return response('User Set not found', 404);
        }

        DownloadImagesForPDF::dispatch($userSet);

        $imageDirectory = "storage/pdf_images/{$userSet->id}/";

        $pdf = Pdf::loadView('user-sets.pdf_view', compact('userSet', 'imageDirectory'));

        return $pdf->download("{$userSet->name}.pdf");
    }
}
