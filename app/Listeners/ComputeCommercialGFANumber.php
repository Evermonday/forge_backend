<?php

namespace App\Listeners;

use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialGFAPercentageUpdated;
use App\Events\GFAUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialGFANumber
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
    public function handle(CommercialGFAPercentageUpdated|GFAUpdated $event): void
    {
        $scenario = $event->scenario;

        if( ($scenario->endUse == Scenario::END_USES_MIXED_USE || $scenario->endUse == Scenario::END_USES_COMMERCIAL)
            && $scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_MANUAL)
        {
            $scenario->commercialGFANumber = $scenario->gfa * ($scenario->commercialGFAPercentage / 100);
            $scenario->save();

            event(new CommercialGFANumberUpdated($scenario));
        }
    }
}
