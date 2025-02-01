<?php

namespace App\Listeners;

use App\Events\CommercialGFAPercentageUpdated;
use App\Events\CommercialNFANumberUpdated;
use App\Events\CommercialNFAPercentageUpdated;
use App\Events\GFAUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeCommercialNFAPercentageBasedOnCommercialNFANumber
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
    public function handle(CommercialNFANumberUpdated $event): void
    {
        $scenario = $event->scenario;

        $newCommercialNFAPercentage = $scenario->commercialGFANumber == 0 ? 0 : ($scenario->commercialNFANumber / $scenario->commercialGFANumber) * 100;
        $newCommercialNFAPercentage = round($newCommercialNFAPercentage, 2);

        if($scenario->commercialNFAPercentage == $newCommercialNFAPercentage)
        {
            return;
        }

        $scenario->commercialNFAPercentage = $newCommercialNFAPercentage;
        $scenario->save();

        event(new CommercialNFAPercentageUpdated($scenario));
    }
}
