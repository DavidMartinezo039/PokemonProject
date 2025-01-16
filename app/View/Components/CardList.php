<?php
namespace App\View\Components;

use Illuminate\View\Component;

class CardList extends Component
{
    public $cards;

    /**
     * Create a new component instance.
     *
     * @param $cards
     */
    public function __construct($cards)
    {
        $this->cards = $cards;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-list');
    }
}
