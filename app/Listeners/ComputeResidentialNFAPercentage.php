<?php

namespace App\Listeners;

use App\Events\GFAUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use App\Events\ResidentialNFANumberUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeResidentialNFAPercentage
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
    public function handle(ResidentialNFANumberUpdated|GFAUpdated $event): void
    {
        $scenario = $event->scenario;

        if($scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE &&
            ($scenario->endUse == Scenario::END_USES_MIXED_USE || $scenario->endUse == Scenario::END_USES_RESIDENTIAL))
        {
            $scenario->residentialGFAPercentage = $scenario->gfa == 0 ? 0 : ($scenario->residentialGFANumber / $scenario->gfa) * 100;
            $scenario->save();

            event(new ResidentialGFAPercentageUpdated($scenario));
        }
    }
}
