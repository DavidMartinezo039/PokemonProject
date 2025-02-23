<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserSet;

class PdfButton extends Component
{
    public $userSet;
    public $isPdfReady = false;

    public function mount(UserSet $userSet)
    {
        $this->userSet = $userSet;
        $this->checkPdfStatus();
    }

    public function checkPdfStatus()
    {
        $pdfPath = public_path('storage/pdfs/user_sets/' . $this->userSet->id . '.pdf');

        $this->isPdfReady = file_exists($pdfPath);
    }

    public function render()
    {
        return view('livewire.pdf-button');
    }
}
