<?php

namespace App\Listeners;

use App\Events\GFAUpdated;
use App\Events\ResidentialGFANumberUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeResidentialGFAPercentageBasedOnGFA
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
            ($scenario->endUse == Scenario::END_USES_MIXED_USE || $scenario->endUse == Scenario::END_USES_RESIDENTIAL)
            && $scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        )
        {
            return;
        }

        $newResidentialGFAPercentage = $scenario->gfa == 0 ? 0 : ($scenario->residentialGFANumber / $scenario->gfa) * 100;
        $newResidentialGFAPercentage = round($newResidentialGFAPercentage, 2);

        if ($scenario->residentialGFAPercentage == $newResidentialGFAPercentage)
        {
            return;
        }

        $scenario->residentialGFAPercentage = $newResidentialGFAPercentage;
        $scenario->save();

        event(new ResidentialGFAPercentageUpdated($scenario));
    }
}
