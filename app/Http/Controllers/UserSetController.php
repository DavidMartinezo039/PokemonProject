<?php

namespace App\Http\Controllers;

use App\Events\UserSetUpdated;
use App\Http\Requests\UserSetRequest;
use App\Jobs\DownloadImagesForPDF;
use App\Models\Card;
use App\Models\UserSet;
use Illuminate\Support\Str;

class UserSetController extends Controller
{
    public function index()
    {
        return view('user-sets.index');
    }

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

        event(new UserSetUpdated(auth()->user(), $userSet, 'created'));

        return redirect()->route('user-sets.index');
    }



    // Mostrar los detalles de un set
    public function show($userSetId)
    {
        $user = auth()->user();

        $userSet = UserSet::with('cards')->find($userSetId);

        if (!$userSet || !$user->can('view', $userSet)) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }


        return view('user-sets.show', compact('userSet'));
    }

    public function showCards($userSetId)
    {
        $user = auth()->user();

        $userSet = UserSet::find($userSetId);

        if (!$userSet || !$user->can('view', $userSet)) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }

        $cards = $userSet->cards()->orderBy('user_set_cards.order_number')->get();

        if ($cards->isEmpty()) {
            session()->flash('message', 'No hay cartas disponibles.');
        }

        DownloadImagesForPDF::dispatch($userSet);


        return view('user-sets.cards', compact('userSet', 'cards'));
    }


    // Mostrar el formulario para editar un set
    public function edit($id)
    {
        $user = auth()->user();

        $userSet = UserSet::find($id);

        if (!$userSet || !$user->can('view', $userSet)) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }


        return view('user-sets.edit', compact('userSet'));
    }

    // Actualizar un set
    public function update(UserSetRequest $request, $id)
    {
        $user = auth()->user();

        $validated = $request->validated();

        $userSet = UserSet::find($id);

        if (!$userSet || !$user->can('view', $userSet)) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }


        if ($request->hasFile('image')) {

            // Obtener la imagen y guardarla
            $imageName = Str::random(40) . '.' . $request->file('image')->extension();

            $imagePath = $request->file('image')->storeAs('user_sets', $imageName, 'public');

            $userSet->image = $imagePath;

        }

        $userSet->name = $validated['name'] ?? $userSet->name;
        $userSet->description = $validated['description'] ?? $userSet->description;

        // Guardar los cambios
        $userSet->save();

        return redirect()->route('user-sets.index')->with('success', 'Set actualizado con éxito');
    }


    // Eliminar un set
    public function destroy($id)
    {
        $user = auth()->user();

        $userSet = UserSet::find($id);

        if (!$userSet || !$user->can('view', $userSet)) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }


        $userSet->delete();

        return redirect()->route('user-sets.index')->with('success', 'Set eliminado con éxito');
    }

    public function selectCard($userSetId)
    {
        $user = auth()->user();

        $userSet = UserSet::find($userSetId);

        if (!$userSet || !$user->can('view', $userSet)) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }


        $cards = Card::paginate(25);

        return view('user-sets.select-card', compact('userSet', 'cards'));
    }


    // Añadir una carta a un set
    public function addCard($userSetId,  $cardId)
    {
        $userSet = UserSet::find($userSetId);

        $card = Card::find($cardId);

        if (!$userSet) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }

        if (!$card) {
            return redirect()->route('user-sets.cards', $userSetId)
                ->with('error', 'La carta no existe.');
        }

        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            return redirect()->route('user-sets.cards', $userSetId)
                ->with('message', 'La carta ya está en este set.');
        }

        $lastOrderNumber = $userSet->cards()->max('order_number');
        $newOrderNumber = $lastOrderNumber ? $lastOrderNumber + 1 : 1;

        $userSet->cards()->attach($cardId, ['order_number' => $newOrderNumber]);

        $userSet->increment('card_count');

        event(new UserSetUpdated(auth()->user(), $userSet, 'added_card', $cardId));

        return redirect()->route('user-sets.cards', $userSetId)
            ->with('success', 'Carta añadida correctamente al set');
    }

    public function myCards($userSetId)
    {
        $user = auth()->user();

        $userSet = UserSet::find($userSetId);

        if (!$userSet || !$user->can('view', $userSet)) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }


        $userSet->cards()->orderBy('user_set_cards.order_number')->get();

        return view('user-sets.my-cards', compact('userSet'));
    }

    public function removeCard($userSetId, $cardId)
    {
        $userSet = UserSet::find($userSetId);

        $card = Card::find($cardId);

        if (!$userSet) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }

        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            $userSet->cards()->detach($cardId);
            $userSet->decrement('card_count');

            $remainingCards = $userSet->cards()->orderBy('order_number')->get();

            foreach ($remainingCards as $index => $card) {
                $userSet->cards()->updateExistingPivot($card->id, ['order_number' => $index + 1]);
            }

            event(new UserSetUpdated(auth()->user(), $userSet, 'removed_card', $cardId));

            return redirect()->route('user-sets.cards', $userSetId)
                ->with('success', 'Carta eliminada correctamente del set');
        } else {
            return redirect()->route('user-sets.cards', $userSetId)
                ->with('message', 'La carta no se encuentra en el set o no existe.');
        }
    }

    private function checkPermissionsAndExistence($userSetId, $cardId = null)
    {
        $user = auth()->user();
        $userSet = UserSet::find($userSetId);

        if (!$userSet || !$user->can('view', $userSet)) {
            abort(404, 'El set solicitado no fue encontrado o no tienes permiso para verlo.');
        }

        if ($cardId) {
            $card = Card::find($cardId);
            if (!$card) {
                return redirect()->route('user-sets.cards', $userSetId)
                    ->with('message', 'La carta no existe.');
            }
        }

        return $userSet;
    }
}
