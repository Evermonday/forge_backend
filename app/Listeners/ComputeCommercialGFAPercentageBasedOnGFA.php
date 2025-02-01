<?php

namespace App\Listeners;

use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialGFAPercentageUpdated;
use App\Events\GFAUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialGFAPercentageBasedOnGFA
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GFAUpdated $event): void
    {
        $scenario = $event->scenario;

        if( !(
            ($scenario->endUse == Scenario::END_USES_MIXED_USE || $scenario->endUse == Scenario::END_USES_COMMERCIAL)
            && $scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        )
        {
            return;
        }

        $newCommercialGFAPercentage = $scenario->gfa == 0 ? 0 : ($scenario->commercialGFANumber / $scenario->gfa) * 100;
        $newCommercialGFAPercentage = round($newCommercialGFAPercentage, 2);

        if($scenario->commercialGFAPercentage == $newCommercialGFAPercentage)
        {
            return;
        }

        $scenario->commercialGFAPercentage = $newCommercialGFAPercentage;
        $scenario->save();

        event(new CommercialGFAPercentageUpdated($scenario));
    }
}