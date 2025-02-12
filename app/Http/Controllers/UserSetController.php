<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSetRequest;
use App\Models\Card;
use App\Models\UserSet;
use Illuminate\Support\Str;

class UserSetController extends Controller
{
// Mostrar la lista de sets
    public function index()
    {
        $userSets = UserSet::orderBy('created_at', 'desc')->get();


        if ($userSets->isEmpty()) {
            return view('user-sets.index')->with('message', 'No sets available');
        }

        return view('user-sets.index', compact('userSets'));
    }

    // Mostrar el formulario para crear un nuevo set
    public function create()
    {
        return view('user-sets.create');
    }

    public function store(UserSetRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imageName = Str::random(40) . '.' . $request->file('image')->extension();
            $imagePath = $request->file('image')->storeAs('user_sets', $imageName, 'public');
        } else {
            $defaultImages = [
                'user_sets/predetermined/default1.png',
                'user_sets/predetermined/default2.png'
            ];

            $imagePath = $defaultImages[array_rand($defaultImages)];
        }

        $userSet = new UserSet();
        $userSet->name = $validated['name'];
        $userSet->description = $validated['description'];
        $userSet->image = $imagePath;
        $userSet->user_id = auth()->id();
        $userSet->save();

        return redirect()->route('user-sets.index');
    }



    // Mostrar los detalles de un set
    public function show($userSetId)
    {
        $userSet = UserSet::with('cards')->findOrFail($userSetId); // Cargar las cartas asociadas al set
        return view('user-sets.show', compact('userSet'));
    }

    public function showCards($id)
    {
        $userSet = UserSet::findOrFail($id);

        $cards = $userSet->cards()->get();

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
            session()->flash('message', 'No hay cartas disponibles.');
        }

        return view('user-sets.cards', compact('userSet', 'cards'));
    }

    // Mostrar el formulario para editar un set
    public function edit($id)
    {
        $userSet = UserSet::findOrFail($id);
        return view('user-sets.edit', compact('userSet'));
    }

    // Actualizar un set
    public function update(UserSetRequest $request, $id)
    {
        $validated = $request->validated();

        $userSet = UserSet::findOrFail($id);

        if ($request->hasFile('image')) {

            // Obtener la imagen y guardarla
            $imageName = Str::random(40) . '.' . $request->file('image')->extension();

            $imagePath = $request->file('image')->storeAs('user_sets', $imageName, 'public');

            $userSet->image = $imagePath;

        }

        $userSet->name = $validated['name'];
        $userSet->description = $validated['description'];

        // Guardar los cambios
        $userSet->save();

        return redirect()->route('user-sets.index')->with('success', 'Set actualizado con éxito');
    }


    // Eliminar un set
    public function destroy($id)
    {
        $userSet = UserSet::findOrFail($id);

        $userSet->delete();

        return redirect()->route('user-sets.index')->with('success', 'Set eliminado con éxito');
    }

    public function selectCard($userSetId)
    {
        $userSet = UserSet::findOrFail($userSetId);
        $cards = Card::paginate(25);

        return view('user-sets.select-card', compact('userSet', 'cards'));
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
            return redirect()->route('user-sets.cards', $userSetId)
                ->with('message', 'La carta no existe.');
        }

        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            return redirect()->route('user-sets.cards', $userSetId)
                ->with('message', 'La carta ya está en este set.');
        }

        $userSet->cards()->attach($cardId);

        $userSet->increment('card_count');

        return redirect()->route('user-sets.cards', $userSetId)
            ->with('success', 'Carta añadida correctamente al set');
    }

    public function myCards($userSetId)
    {
        $userSet = UserSet::findOrFail($userSetId);

        return view('user-sets.my-cards', compact('userSet'));
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
            return redirect()->route('user-sets.cards', $userSetId)
                ->with('message', 'La carta no existe.');
        }

        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            $userSet->cards()->detach($cardId);
            $userSet->decrement('card_count');
        } else {
            return redirect()->route('user-sets.cards', $userSetId)
                ->with('message', 'La carta no se encuentra en el set.');
        }

        return redirect()->route('user-sets.cards', $userSetId)
            ->with('success', 'Carta eliminada correctamente del set');
    }
}
