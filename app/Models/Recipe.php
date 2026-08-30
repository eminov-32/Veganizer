<?php

namespace App\Models;

use Database\Factories\RecipeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recipe extends Model
{
    /** @use HasFactory<RecipeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'instructions',
        'servings',
        'prep_minutes',
        'cook_minutes',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'servings' => 'integer',
            'prep_minutes' => 'integer',
            'cook_minutes' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(RecipeIngredient::class)->orderBy('position');
    }
}
