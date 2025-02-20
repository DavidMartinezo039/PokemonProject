<?php

namespace App\Http\Controllers;
use App\Models\UserSet;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    public function generatePDF(UserSet $userSet)
    {
        $filePath = "pdfs/user_sets/{$userSet->id}.pdf";

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, "El archivo no existe.");
        }

        return response()->download(storage_path("app/public/{$filePath}"));
    }

}
