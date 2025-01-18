<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserSet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'images', 'user_id'];

    // Relación muchos a muchos con cartas
    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'user_set_cards', 'user_set_id', 'card_id');
    }

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}

