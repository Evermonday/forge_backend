<?php

namespace App\Listeners;

use App\Events\CommercialNFANumberUpdated;
use App\Events\CommercialNFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialNFANumberBasedOnCommercialNFAPercentage
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
    public function handle(CommercialNFAPercentageUpdated $event): void
    {
        $scenario = $event->scenario;

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
