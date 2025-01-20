<?php

namespace App\Http\Controllers;

use App\Models\UserSet;

class UserSetController extends Controller
{

    public function addCardToUserSet($userSetId, $cardId)
    {
        $userSet = UserSet::findOrFail($userSetId);

        $userSet->cards()->attach($cardId);

        $userSet->increment('card_count');

        return response()->json([
            'message' => 'Carta añadida correctamente al set',
            'card_count' => $userSet->card_count,
        ]);
    }

    public function removeCardFromUserSet($userSetId, $cardId)
    {
        $userSet = UserSet::findOrFail($userSetId);

        $userSet->cards()->detach($cardId);

        $userSet->decrement('card_count');

        return response()->json([
            'message' => 'Carta eliminada correctamente del set',
            'card_count' => $userSet->card_count,
        ]);
    }

}
