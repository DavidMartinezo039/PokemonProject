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
        $user = auth()->user();

        $userSets = UserSet::forUser($user)
            ->search($this->searchTerm)
            ->orderBy('created_at', 'desc')
            ->get();

        $message = $userSets->isEmpty() ? 'No sets available' : null;

        return view('livewire.user-set-search', compact('userSets', 'message'));
    }




    public function search()
    {
        $this->render();
    }
}
