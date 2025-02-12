<?php

namespace App\Livewire;

use App\Models\UserSet;
use Livewire\Component;
use Livewire\WithPagination;

class UserSetSearch extends Component
{
    use WithPagination;

    public $searchTerm = '';

    public function render()
    {
        $userSets = $this->searchTerm
            ? UserSet::where('name', 'LIKE', "%$this->searchTerm%")
                ->orderBy('created_at', 'desc')
                ->get()
            : UserSet::orderBy('created_at', 'desc')
                ->get();

        $message = $userSets->isEmpty() ? 'No sets available' : null;

        return view('livewire.user-set-search', [
            'userSets' => $userSets,
            'message' => $message,
        ]);
    }


    public function search()
    {
        $this->render();
    }
}
