<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function index()
    {
        $sets = Set::all();
        return view('sets.index', compact('sets'));
    }

    public function show($id)
    {
        $set = Set::findOrFail($id);
        $images = json_decode($set->images, true);
        return view('sets.show', compact('set', 'images'));
    }

    public function showCards($id)
    {
        $set = Set::findOrFail($id);
        $cards = $set->cards;
        return view('sets.cards', compact('set', 'cards'));
    }

    public function create()
    {
        return view('sets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $set = Set::create($request->all());
        return redirect()->route('sets.index')->with('success', 'Set creado correctamente');
    }

    public function edit($id)
    {
        $set = Set::findOrFail($id);
        return view('sets.edit', compact('set'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $set = Set::findOrFail($id);
        $set->update($request->all());
        return redirect()->route('sets.index')->with('success', 'Set actualizado correctamente');
    }

    public function destroy($id)
    {
        $set = Set::findOrFail($id);
        $set->delete();
        return redirect()->route('sets.index')->with('success', 'Set eliminado correctamente');
    }
}
