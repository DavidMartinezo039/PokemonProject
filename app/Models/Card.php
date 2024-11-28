<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'supertype',
        'subtypes',
        'level',
        'hp',
        'types',
        'evolves_to',
        'attacks',
        'weaknesses',
        'retreat_cost',
        'converted_retreat_cost',
        'set_id',
        'set_name',
        'rarity',
        'flavor_text',
        'national_pokedex_numbers',
        'small_image_url',
        'large_image_url',
        'tcgplayer_url',
        'cardmarket_url',
        'prices',
    ];

    protected $casts = [
        'subtypes' => 'array',
        'types' => 'array',
        'evolves_to' => 'array',
        'attacks' => 'array',
        'weaknesses' => 'array',
        'retreat_cost' => 'array',
        'national_pokedex_numbers' => 'array',
        'prices' => 'array',
    ];
}
