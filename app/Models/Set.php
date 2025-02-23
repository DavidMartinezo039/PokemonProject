<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $casts = [
        'legalities' => 'array',
        'images' => 'array',
    ];

    protected $fillable = [
        'id',
        'name',
        'series',
        'printedTotal',
        'total',
        'legalities',
        'ptcgoCode',
        'releaseDate',
        'updatedAt',
        'images',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }
}
