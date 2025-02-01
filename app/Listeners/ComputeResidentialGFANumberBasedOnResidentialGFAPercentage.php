<?php

namespace App\Listeners;

use App\Events\ResidentialGFANumberUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeResidentialGFANumberBasedOnResidentialGFAPercentage
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
    public function handle(ResidentialGFAPercentageUpdated $event): void
    {
        $scenario = $event->scenario;

        $newResidentialGFANumber = $scenario->gfa * ($scenario->residentialGFAPercentage / 100);
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
