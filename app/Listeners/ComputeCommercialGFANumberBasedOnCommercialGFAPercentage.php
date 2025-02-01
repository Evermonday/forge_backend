<?php

namespace App\Listeners;

use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialGFAPercentageUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialGFANumberBasedOnCommercialGFAPercentage
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
    public function handle(CommercialGFAPercentageUpdated $event): void
    {
        $scenario = $event->scenario;
        
        $newCommercialGFANumber = $scenario->gfa * ($scenario->commercialGFAPercentage / 100);
        $newCommercialGFANumber = round($newCommercialGFANumber, 2);

        if($scenario->commercialGFANumber == $newCommercialGFANumber)
        {
            return;
        }

        $scenario->commercialGFANumber = $newCommercialGFANumber;
        $scenario->save();

        event(new CommercialGFANumberUpdated($scenario));
    }
}
