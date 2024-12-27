<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rarity extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name'];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
