<?php

namespace App\Listeners;

use App\Events\ResidentialGFAPercentageUpdated;
use App\Events\ResidentialNFANumberUpdated;
use App\Events\ResidentialNFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use function Illuminate\Log\log;

class ComputeResidentialNFANumberBasedOnResidentialNFAPercentage
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
    public function handle(ResidentialNFAPercentageUpdated $event): void
    {
        $scenario = $event->scenario;

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
