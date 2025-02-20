<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function index()
    {
        return response()->json(Set::all());
    }

    public function show(Set $set)
    {
        return response()->json($set);
    }

    public function showCards(Set $set)
    {
        return response()->json($set->cards);
    }
}
