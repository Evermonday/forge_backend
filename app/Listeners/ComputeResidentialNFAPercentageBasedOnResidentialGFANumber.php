<?php

namespace App\Listeners;

use App\Events\ResidentialGFANumberUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use App\Events\ResidentialNFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeResidentialNFAPercentageBasedOnResidentialGFANumber
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
    public function handle(ResidentialGFANumberUpdated $event): void
    {
        $scenario = $event->scenario;

        if($scenario->nfaAreaAllocMethod == Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        {
            return;
        }

        $newResidentialNFAPercentage = $scenario->residentialGFANumber == 0 ? 0 : ($scenario->residentialNFANumber / $scenario->residentialGFANumber) * 100;
        $newResidentialNFAPercentage = round($newResidentialNFAPercentage, 2);

        if($scenario->residentialNFAPercentage == $newResidentialNFAPercentage)
        {
            return;    
        }

        $scenario->residentialNFAPercentage = $newResidentialNFAPercentage;
        $scenario->save();

        event(new ResidentialNFAPercentageUpdated($scenario));
    }
}
