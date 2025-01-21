<?php

namespace App\Http\Controllers;

use App\Models\UserSet;

class UserSetController extends Controller
{
// Mostrar la lista de sets
    public function index()
    {
        $sets = UserSet::all(); // Obtener todos los sets
        return view('user_sets.index', compact('sets'));
    }

    // Mostrar el formulario para crear un nuevo set
    public function create()
    {
        return view('user_sets.create');
    }

    // Almacenar un nuevo set
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $userSet = UserSet::create($request->all());

        return redirect()->route('user-sets.index')->with('message', 'Set creado con éxito');
    }

    // Mostrar los detalles de un set
    public function show($id)
    {
        $set = UserSet::with('cards')->findOrFail($id); // Cargar las cartas asociadas al set
        return view('user_sets.show', compact('set'));
    }

    // Mostrar el formulario para editar un set
    public function edit($id)
    {
        $set = UserSet::findOrFail($id);
        return view('user_sets.edit', compact('set'));
    }

    // Actualizar un set
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $set = UserSet::findOrFail($id);
        $set->update($request->all());

        return redirect()->route('user-sets.index')->with('message', 'Set actualizado con éxito');
    }

    // Eliminar un set
    public function destroy($id)
    {
        $set = UserSet::findOrFail($id);
        $set->delete();

        return redirect()->route('user-sets.index')->with('message', 'Set eliminado con éxito');
    }

    // Añadir una carta a un set
    public function addCard($userSetId, $cardId)
    {
        $userSet = UserSet::findOrFail($userSetId);
        $userSet->cards()->attach($cardId);
        $userSet->increment('card_count');

        return redirect()->route('user-sets.show', $userSetId);
    }

    // Eliminar una carta de un set
    public function removeCard($userSetId, $cardId)
    {
        $userSet = UserSet::findOrFail($userSetId);
        $userSet->cards()->detach($cardId);
        $userSet->decrement('card_count');

        return redirect()->route('user-sets.show', $userSetId);
    }
}
