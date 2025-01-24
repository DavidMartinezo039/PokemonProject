<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSetRequest;
use App\Models\Card;
use App\Models\UserSet;

class UserSetController extends Controller
{
// Mostrar la lista de sets
    public function index()
    {
        $sets = UserSet::all(); // Obtener todos los sets
        return view('user-sets.index', compact('sets'));
    }

    // Mostrar el formulario para crear un nuevo set
    public function create()
    {
        return view('user-sets.create');
    }

    // Almacenar un nuevo set
    public function store(UserSetRequest $request)
    {
        UserSet::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $request->file('image') ? $request->file('image')->store('user-sets') : null,
            'user_id' => auth()->id(),
            'card_count' => 0,
        ]);

        return redirect()->route('user-sets.index')->with('message', 'Set creado con éxito');
    }


    // Mostrar los detalles de un set
    public function show($userSetId)
    {
        $set = UserSet::with('cards')->findOrFail($userSetId); // Cargar las cartas asociadas al set
        return view('user-sets.show', compact('set'));
    }

    // Mostrar el formulario para editar un set
    public function edit($id)
    {
        $set = UserSet::findOrFail($id);
        return view('user-sets.edit', compact('set'));
    }

    // Actualizar un set
    public function update(UserSetRequest $request, $id)
    {
        $validated = $request->validated();

        $set = UserSet::findOrFail($id);

        $set->update($validated);

        return redirect()->route('user-sets.index')->with('success', 'Set actualizado con éxito');
    }


    // Eliminar un set
    public function destroy($id)
    {
        $set = UserSet::findOrFail($id);

        $set->delete();

        return redirect()->route('user-sets.index')->with('success', 'Set eliminado con éxito');
    }

    // Añadir una carta a un set
    public function addCard($userSetId,  $cardId)
    {
        $userSet = UserSet::find($userSetId);
        $card = Card::find($cardId);

        if (!$userSet) {
            return redirect()->route('user-sets.index')
                ->with('message', 'El set no existe.');
        }

        if (!$card) {
            return redirect()->route('user-sets.show', $userSetId)
                ->with('message', 'La carta no existe.');
        }

        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            return redirect()->route('user-sets.show', $userSetId)
                ->with('message', 'La carta ya está en este set.');
        }

        $userSet->cards()->attach($cardId);

        $userSet->increment('card_count');

        return redirect()->route('user-sets.show', $userSetId)
            ->with('success', 'Carta añadida correctamente al set');
    }

    // Eliminar una carta de un set
    public function removeCard($userSetId, $cardId)
    {
        $userSet = UserSet::find($userSetId);
        $card = Card::find($cardId);

        if (!$userSet) {
            return redirect()->route('user-sets.index')
                ->with('message', 'El set no existe.');
        }

        if (!$card) {
            return redirect()->route('user-sets.show', $userSetId)
                ->with('message', 'La carta no existe.');
        }

        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            $userSet->cards()->detach($cardId);
            $userSet->decrement('card_count');
        } else {
            return redirect()->route('user-sets.show', $userSetId)
                ->with('message', 'La carta no se encuentra en el set.');
        }

        return redirect()->route('user-sets.show', $userSetId)
            ->with('success', 'Carta eliminada correctamente del set');
    }
}
