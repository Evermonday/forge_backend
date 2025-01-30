<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scenario extends Model
{

    public const MEASUREMENT_UNITS = ['metric', 'imperial'];
    public const DEVELOPMENT_STRATEGIES = ['Build-to-Rent', 'Build-to-Sell', 'Hybrid'];
    public const END_USES = ['Residential', 'Commercial', 'Mixed Use'];
    public const END_USES_RESIDENTIAL = 'Residential';
    public const END_USES_COMMERCIAL = 'Commercial';
    public const END_USES_MIXED_USE = 'Mixed Use';
    
    public const UNIT_TYPES = ['Apartment', 'Row', 'Other'];

    public const GFA_CALC_METHODS = ['FSI-Based', 'Manual'];
    public const GFA_CALC_METHOD_FSI_BASED = 'FSI-Based';
    public const GFA_CALC_METHOD_MANUAL = 'Manual';
    
    public const NFA_AREA_ALLOC_METHODS = ['Manual', 'Percentile'];
    public const AREA_ALLOC_METHODS = ['Manual', 'Percentile'];
    public const AREA_ALLOC_METHOD_MANUAL = 'Manual';
    public const AREA_ALLOC_METHOD_PERCENTILE = 'Percentile';
    public const DEVELOPMENT_TYPES = [
        'New Construction',
        'Renovation/Remodeling',
        'Repair/Maintenance',
        'Other',
    ];

    /**
     * Get the tag assigned to this scenario.
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the project that owns this scenario.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
