<?php

namespace App\Listeners;

use App\Models\Scenario;
use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialGFAPercentageUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialGFAPercentageBasedOnCommercialGFANumber
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

        $newCommercialGFAPercentage = $scenario->gfa == 0 ? 0 : ($scenario->commercialGFANumber / $scenario->gfa) * 100;
        $newCommercialGFAPercentage = round($newCommercialGFAPercentage, 2);

        if($scenario->commercialGFAPercentage == $newCommercialGFAPercentage)
        {
            return;
        }

        $scenario->commercialGFAPercentage = $newCommercialGFAPercentage;
        $scenario->save();

        event(new CommercialGFAPercentageUpdated($scenario));
    }
}
