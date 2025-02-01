<?php

namespace App\Listeners;

use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialGFAPercentageUpdated;
use App\Events\GFAUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialGFANumberBasedOnGFA
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
            && $scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_MANUAL)
        )
        {
            return;
        }

        $newCommercialGFANumber = $scenario->gfa * ($scenario->commercialGFAPercentage / 100);
        $newCommercialGFANumber = round($newCommercialGFANumber, 2);

        if($scenario->commercialGFANumber == $newCommercialGFANumber)
        {
            return;
        }

        $scenario->commercialGFANumber = $newCommercialGFANumber;
        $scenario->save();

        event(new CommercialGFANumberUpdated($scenario));
    }
}
