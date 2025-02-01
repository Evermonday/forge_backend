<?php

namespace App\Listeners;

use App\Events\GFAUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use App\Events\ResidentialNFANumberUpdated;
use App\Events\ResidentialNFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use function Illuminate\Log\log;

class ComputeResidentialNFAPercentageBasedOnResidentialNFANumber
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
    public function handle(ResidentialNFANumberUpdated $event): void
    {
        $scenario = $event->scenario;

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
