<?php

namespace App\Http\Controllers;

use App\Events\UserSetCreated;
use App\Events\UserSetUpdated;
use App\Http\Requests\UserSetRequest;
use App\Jobs\GenerateUserSetPdf;
use App\Models\Card;
use App\Models\UserSet;
use Illuminate\Support\Facades\Gate;
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
                'View/predetermined/default1.png',
                'View/predetermined/default2.png'
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
        event(new UserSetCreated($userSet));

        return redirect()->route('user-sets.index');
    }

    public function show(UserSet $userSet)
    {
        Gate::authorize('view', $userSet);

        return view('user-sets.show', compact('userSet'));
    }

    public function showCards(UserSet $userSet)
    {
        Gate::authorize('view', $userSet);

        $cards = $userSet->cards()->orderBy('user_set_cards.order_number')->get();

        if ($cards->isEmpty()) {
            session()->flash('message', __('No cards available'));
        }

        return view('user-sets.cards', compact('userSet', 'cards'));
    }


    public function edit(UserSet $userSet)
    {
        Gate::authorize('view', $userSet);

        return view('user-sets.edit', compact('userSet'));
    }

    // Actualizar un set
    public function update(UserSetRequest $request, UserSet $userSet)
    {
        $validated = $request->validated();

        Gate::authorize('view', $userSet);

        if ($request->hasFile('image')) {

            $imageName = Str::random(40) . '.' . $request->file('image')->extension();

            $imagePath = $request->file('image')->storeAs('user_sets', $imageName, 'public');

            $userSet->image = $imagePath;

        }

        $userSet->name = $validated['name'] ?? $userSet->name;
        $userSet->description = $validated['description'] ?? $userSet->description;

        $userSet->save();

        return redirect()->route('user-sets.index')->with('success', __('Set successfully updated'));
    }


    // Eliminar un set
    public function destroy(UserSet $userSet)
    {
        Gate::authorize('view', $userSet);

        $userSet->delete();

        return redirect()->route('user-sets.index')->with('success', __('Set successfully removed'));
    }

    public function selectCard(UserSet $userSet)
    {
        Gate::authorize('view', $userSet);

        $cards = Card::paginate(25);

        return view('user-sets.select-card', compact('userSet', 'cards'));
    }


    // Añadir una carta a un set
    public function addCard(UserSet $userSet, Card $card)
    {
        if ($userSet->cards()->where('card_id', $card->id)->exists()) {
            return redirect()->route('user-sets.cards', $userSet->id)
                ->with('message', __('The card is already in the set'));
        }

        $lastOrderNumber = $userSet->cards()->max('order_number');
        $newOrderNumber = $lastOrderNumber ? $lastOrderNumber + 1 : 1;

        $userSet->cards()->attach($card->id, ['order_number' => $newOrderNumber]);

        $userSet->increment('card_count');

        event(new UserSetUpdated(auth()->user(), $userSet, 'added_card', $card->id));
        event(new GenerateUserSetPdf($userSet));

        return redirect()->route('user-sets.cards', $userSet->id)
            ->with('success', __('Card successfully added to the set'));
    }

    public function myCards(UserSet $userSet)
    {
        Gate::authorize('view', $userSet);

        $userSet->cards()->orderBy('user_set_cards.order_number')->get();

        return view('user-sets.my-cards', compact('userSet'));
    }

    public function removeCard(UserSet $userSet, Card $card)
    {
        if ($userSet->cards()->where('card_id', $card->id)->exists()) {
            $userSet->cards()->detach($card->id);
            $userSet->decrement('card_count');

            $remainingCards = $userSet->cards()->orderBy('order_number')->get();

            foreach ($remainingCards as $index => $card) {
                $userSet->cards()->updateExistingPivot($card->id, ['order_number' => $index + 1]);
            }

            event(new UserSetUpdated(auth()->user(), $userSet, 'removed_card', $card->id));
            event(new GenerateUserSetPdf($userSet));

            return redirect()->route('user-sets.cards', $userSet->id)
                ->with('success', __('Card successfully removed from the set'));
        } else {
            return redirect()->route('user-sets.cards', $userSet->id)
                ->with('message', __('The card is not in the set or does not exist'));
        }
    }
}
