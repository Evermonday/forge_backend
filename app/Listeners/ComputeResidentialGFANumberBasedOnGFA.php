<?php

namespace App\Listeners;

use App\Events\GFAUpdated;
use App\Events\ResidentialGFANumberUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeResidentialGFANumberBasedOnGFA
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
            && $scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_MANUAL)
        )
        {
            return;
        }

        $newResidentialGFANumber = $scenario->residentialGFAPercentage == 0 ? 0 : $scenario->gfa * ($scenario->residentialGFAPercentage / 100);
        $newResidentialGFANumber = round($newResidentialGFANumber, 2);

        if($scenario->residentialGFANumber == $newResidentialGFANumber)
        {
            return;
        }

        $scenario->residentialGFANumber = $newResidentialGFANumber;
        $scenario->save();

        event(new ResidentialGFANumberUpdated($scenario));
        
    }
}
