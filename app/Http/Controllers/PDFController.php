<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    public function generatePDF($userSetId)
    {
        $filePath = "pdfs/user_sets/{$userSetId}.pdf";

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, "El archivo no existe.");
        }

        return response()->download(storage_path("app/public/{$filePath}"));
    }

}
