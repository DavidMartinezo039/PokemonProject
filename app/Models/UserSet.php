<?php

namespace App\Models;

use App\Events\GeneratePDF;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserSet extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'image', 'user_id', 'card_count'];

    protected static function booted()
    {
        static::updated(fn($set) => event(new GeneratePDF($set)));
        static::created(fn($set) => event(new GeneratePDF($set)));
    }

    public function scopeForUser($query, $user)
    {
        return $user->can('viewAny', UserSet::class)
            ? $query
            : $query->where('user_id', $user->id);
    }

    public function scopeSearch($query, $term)
    {
        return $query->when($term, fn($q) => $q->where('name', 'LIKE', "%$term%"));
    }

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

