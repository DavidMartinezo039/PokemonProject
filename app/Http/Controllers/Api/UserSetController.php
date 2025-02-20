<?php

namespace App\Http\Controllers\Api;

use App\Events\UserSetUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserSetRequest;
use App\Jobs\GenerateUserSetPdf;
use App\Models\Card;
use App\Models\UserSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserSetController extends Controller
{
    public function index()
    {
        return response()->json(UserSet::with('cards')->get());
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
        $userSet = UserSet::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => $imagePath,
            'user_id' => Auth::id(),
        ]);

        return response()->json($userSet, 201);
    }

    public function show($id)
    {
        $userSet = UserSet::with('cards')->findOrFail($id);
        return response()->json($userSet);
    }

    public function update(UserSetRequest $request, $id)
    {
        $userSet = UserSet::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $imageName = Str::random(40) . '.' . $request->file('image')->extension();

            $imagePath = $request->file('image')->storeAs('user_sets', $imageName, 'public');

            $userSet->image = $imagePath;
        }

        $userSet->update($validated);
        return response()->json($userSet);
    }

    public function destroy($id)
    {
        $userSet = UserSet::findOrFail($id);

        $userSet->delete();
        return response()->json(['message' => 'Set eliminado con éxito']);
    }

    public function addCard($userSetId, $cardId)
    {
        $userSet = UserSet::findOrFail($userSetId);
        $card = Card::findOrFail($cardId);

        if (!$userSet->cards()->where('card_id', $cardId)->exists()) {
            $userSet->cards()->attach($cardId, ['order_number' => $userSet->cards()->max('order_number') + 1]);
            $userSet->increment('card_count');
        }

        return response()->json(['message' => 'Carta añadida correctamente al set']);
    }

    public function removeCard($userSetId, $cardId)
    {
        $userSet = UserSet::findOrFail($userSetId);
        $card = Card::findOrFail($cardId);

        if ($userSet->cards()->where('card_id', $cardId)->exists()) {
            $userSet->cards()->detach($cardId);
            $userSet->decrement('card_count');
        }

        return response()->json(['message' => 'Carta eliminada correctamente del set']);
    }
}
