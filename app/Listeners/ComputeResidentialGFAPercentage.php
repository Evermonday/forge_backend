<?php

namespace App\Listeners;

use App\Events\GFAUpdated;
use App\Events\ResidentialGFANumberUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeResidentialGFAPercentage
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
    public function handle(ResidentialGFANumberUpdated|GFAUpdated $event): void
    {
        $scenario = $event->scenario;

        if( ($scenario->endUse == Scenario::END_USES_MIXED_USE || $scenario->endUse == Scenario::END_USES_RESIDENTIAL)
            && $scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        {
            $scenario->residentialGFAPercentage = $scenario->gfa == 0 ? 0 : ($scenario->residentialGFANumber / $scenario->gfa) * 100;
            $scenario->save();

            event(new ResidentialGFAPercentageUpdated($scenario));
        }
    }
}
