<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserSet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'user_id', 'card_count'];

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'user_set_cards', 'user_set_id', 'card_id')
            ->withPivot('order_number');
    }


    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}

