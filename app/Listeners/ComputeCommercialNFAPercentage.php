<?php

namespace App\Listeners;

use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialGFAPercentageUpdated;
use App\Events\CommercialNFANumberUpdated;
use App\Events\GFAUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialNFAPercentage
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
    public function handle(CommercialNFANumberUpdated|CommercialGFANumberUpdated $event): void
    {
        $scenario = $event->scenario;

        if($scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE &&
            ($scenario->endUse == Scenario::END_USES_MIXED_USE || $scenario->endUse == Scenario::END_USES_COMMERCIAL))
        {
            $scenario->commercialGFAPercentage = $scenario->gfa == 0 ? 0 : ($scenario->commercialGFANumber / $scenario->gfa) * 100;
            $scenario->save();

            event(new CommercialGFAPercentageUpdated($scenario));
        }
    }
}
