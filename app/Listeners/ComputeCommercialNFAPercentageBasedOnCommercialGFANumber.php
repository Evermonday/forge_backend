<?php

namespace App\Listeners;

use App\Models\Scenario;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialNFAPercentageUpdated;

class ComputeCommercialNFAPercentageBasedOnCommercialGFANumber
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
    public function handle(CommercialGFANumberUpdated $event): void
    {
        $scenario = $event->scenario;

        if($scenario->nfaAreaAllocMethod == Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        {
            return;
        }

        $newCommercialNFAPercentage = $scenario->commercialGFANumber == 0 ? 0 : ($scenario->commercialNFANumber / $scenario->commercialGFANumber) * 100;
        $newCommercialNFAPercentage = round($newCommercialNFAPercentage, 2);

        if($scenario->commercialNFAPercentage == $newCommercialNFAPercentage)
        {
            return;
        }

        $scenario->commercialNFAPercentage = $newCommercialNFAPercentage;
        $scenario->save();

        event(new CommercialNFAPercentageUpdated($scenario));
    }
}
