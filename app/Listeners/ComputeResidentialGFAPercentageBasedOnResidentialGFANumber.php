<?php

namespace App\Listeners;

use App\Events\ResidentialGFANumberUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeResidentialGFAPercentageBasedOnResidentialGFANumber
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

        $newResidentialGFAPercentage = $scenario->gfa == 0 ? 0 : ($scenario->residentialGFANumber / $scenario->gfa) * 100;
        $newResidentialGFAPercentage = round($newResidentialGFAPercentage, 2);

        if ($scenario->residentialGFAPercentage == $newResidentialGFAPercentage)
        {
            return;
        }

        $scenario->residentialGFAPercentage = $newResidentialGFAPercentage;
        $scenario->save();

        event(new ResidentialGFAPercentageUpdated($scenario));
    }
}
