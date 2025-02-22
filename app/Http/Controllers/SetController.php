<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function index()
    {
        // Obtener los sets ordenados por fecha de lanzamiento
        $sets = Set::orderBy('releaseDate', 'desc')->get();

        // Agrupar los sets por serie
        $setsBySeries = $sets->groupBy('series');

        if ($sets->isEmpty()) {
            return view('sets.index')->with('message', __('No sets available'));
        }

        return view('sets.index', compact('setsBySeries'));
    }

    public function show($id)
    {
        $set = Set::findOrFail($id);
        return view('sets.show', compact('set'));
    }

    public function showCards($id)
    {
        $set = Set::findOrFail($id);

        $cards = $set->cards()->get();

        // Ordenamos las cartas en PHP después de obtenerlas
        $cards = $cards->sort(function ($a, $b) {
            // Primero intentamos comparar las cartas numéricas
            if (preg_match('/^\d+$/', $a->number) && preg_match('/^\d+$/', $b->number)) {
                return (int)$a->number <=> (int)$b->number;
            }

            // Si ambos tienen letras, primero comparamos las letras y luego los números
            if (preg_match('/^[a-zA-Z]+[0-9]+$/', $a->number) && preg_match('/^[a-zA-Z]+[0-9]+$/', $b->number)) {
                $lettersA = preg_replace('/[^a-zA-Z]/', '', $a->number);
                $numbersA = (int)preg_replace('/\D/', '', $a->number);
                $lettersB = preg_replace('/[^a-zA-Z]/', '', $b->number);
                $numbersB = (int)preg_replace('/\D/', '', $b->number);

                if ($lettersA == $lettersB) {
                    return $numbersA <=> $numbersB;
                }

                return strcmp($lettersA, $lettersB);
            }

            return 0;
        });

        if ($cards->isEmpty()) {
            session()->flash('message', __('No cards available'));
        }

        return view('sets.cards', compact('set', 'cards'));
    }
    /*


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


    */
}
