<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subtype extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'subtypes_cards');
    }
}
