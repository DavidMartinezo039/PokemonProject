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

    public function showCards($userSetId)
    {
        $userSet = UserSet::findOrFail($userSetId);

        $cards = $userSet->cards()->orderBy('user_set_cards.order_number')->get();

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

        $lastOrderNumber = $userSet->cards()->max('order_number');
        $newOrderNumber = $lastOrderNumber ? $lastOrderNumber + 1 : 1;

        $userSet->cards()->attach($cardId, ['order_number' => $newOrderNumber]);

        $userSet->increment('card_count');

        return redirect()->route('user-sets.cards', $userSetId)
            ->with('success', 'Carta añadida correctamente al set');
    }

    public function myCards($userSetId)
    {
        $userSet = UserSet::findOrFail($userSetId);

        $userSet->cards()->orderBy('user_set_cards.order_number')->get();

        return view('user-sets.my-cards', compact('userSet'));
    }

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

        // Verificar si la carta está en el set
        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            // Desasociar la carta del set
            $userSet->cards()->detach($cardId);
            $userSet->decrement('card_count');

            // Obtener las cartas restantes, ordenadas por el número de orden
            $remainingCards = $userSet->cards()->orderBy('order_number')->get();

            // Reajustar el número de orden
            foreach ($remainingCards as $index => $card) {
                // Aquí actualizamos el order_number de las cartas restantes
                $userSet->cards()->updateExistingPivot($card->id, ['order_number' => $index + 1]);
            }

            return redirect()->route('user-sets.cards', $userSetId)
                ->with('success', 'Carta eliminada correctamente del set');
        } else {
            return redirect()->route('user-sets.cards', $userSetId)
                ->with('message', 'La carta no se encuentra en el set.');
        }
    }
}
