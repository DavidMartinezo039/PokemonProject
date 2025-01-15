<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::paginate(50);
        $message = $cards->isEmpty() ? 'No cards available' : null;

        // Pasa siempre la variable $cards a la vista
        return view('cards.index', compact('cards', 'message'));
    }


    public function show($id)
    {
        $card = Card::with('set')->findOrFail($id);
        return view('cards.show', compact('card'));
    }

    public function create()
    {
        return view('cards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'set_id' => 'required|exists:sets,id',
        ]);

        $card = Card::create($request->all());
        return redirect()->route('cards.index')->with('success', 'Carta creada correctamente');
    }

    public function edit($id)
    {
        $card = Card::findOrFail($id);
        return view('cards.edit', compact('card'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'set_id' => 'required|exists:sets,id',
        ]);

        $card = Card::findOrFail($id);
        $card->update($request->all());
        return redirect()->route('cards.index')->with('success', 'Carta actualizada correctamente');
    }

    public function destroy($id)
    {
        $card = Card::findOrFail($id);
        $card->delete();
        return redirect()->route('cards.index')->with('success', 'Carta eliminada correctamente');
    }
}
