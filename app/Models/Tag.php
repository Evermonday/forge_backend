<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    protected $fillable = ['name', 'color'];

    /**
     * The default tags that are used for 
     */
    public const DEFAULT_TAGS = [
        ['name' => 'Concept', 'color' => '#FF0000'],
        ['name' => 'Modeling', 'color' => '#00FF00'],
        ['name' => 'Validated', 'color' => '#0000FF'],
        ['name' => 'Finalized', 'color' => '#000000'],
        ['name' => 'Discarded', 'color' => '#111111']
    ];
    
    /**
     * Get the user that owns the tag.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the scenarios that are the tagged with this tag.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Scenario::class);
    }
}




