<?php

namespace App\Listeners;

use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialNFANumberUpdated;
use App\Events\CommercialNFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialNFANumberBasedOnCommercialGFANumber
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

        if( $scenario->nfaAreaAllocMethod == Scenario::AREA_ALLOC_METHOD_MANUAL)
        {
            return;
        }

        $newCommercialNFANumber = $scenario->commercialGFANumber * ($scenario->commercialNFAPercentage / 100);
        $newCommercialNFANumber = round($newCommercialNFANumber, 2);

        if($scenario->commercialNFANumber == $newCommercialNFANumber)
        {
            return;
        }

        $scenario->commercialNFANumber = $newCommercialNFANumber;
        $scenario->save();

        event(new CommercialNFANumberUpdated($scenario));
    }
}
