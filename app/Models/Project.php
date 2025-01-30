<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    /**
     * Get all the scenarios that belong to this project
     */
    public function scenarios(): HasMany
    {
        return $this->hasMany(Scenario::class);
    }
}
