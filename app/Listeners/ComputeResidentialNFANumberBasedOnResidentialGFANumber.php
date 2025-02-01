<?php

namespace App\Listeners;

use App\Events\ResidentialGFANumberUpdated;
use App\Models\Scenario;
use App\Events\ResidentialNFANumberUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeResidentialNFANumberBasedOnResidentialGFANumber
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

        if( $scenario->nfaAreaAllocMethod == Scenario::AREA_ALLOC_METHOD_MANUAL)
        {
            return;
        }

        $newResidentialNFANumber = $scenario->residentialGFANumber * ($scenario->residentialNFAPercentage / 100);
        $newResidentialNFANumber = round($newResidentialNFANumber, 2);

        if($scenario->residentialNFANumber == $newResidentialNFANumber)
        {
            return;
        }

        $scenario->residentialNFANumber = $newResidentialNFANumber;
        $scenario->save();

        event(new ResidentialNFANumberUpdated($scenario));
    }
}
