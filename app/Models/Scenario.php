<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Scenario extends Model
{

    public const MEASUREMENT_UNITS = ['metric', 'imperial'];
    public const DEVELOPMENT_STRATEGIES = ['Build-to-Rent', 'Build-to-Sell', 'Hybrid'];
    public const END_USES = ['Residential', 'Commercial', 'Mixed Use'];
    public const UNIT_TYPES = ['Apartment', 'Row', 'Other'];
    public const GFA_CALC_METHODS = ['FSI-Based', 'Manual'];
    public const NFA_AREA_ALLOC_METHODS = ['Manual', 'Percentile'];
    public const AREA_ALLOC_METHODS = ['Manual', 'Percentile'];
    public const DEVELOPMENT_TYPES = [
        'New Construction',
        'Renovation/Remodeling',
        'Repair/Maintenance',
        'Other',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
