<?php

namespace App\Listeners;

use App\Events\EndUseUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use function Illuminate\Log\log;

class DivvyUpGFAValues
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
    public function handle(EndUseUpdated $event): void
    {
        $scenario = $event->scenario;

        if ($scenario->endUse == Scenario::END_USES_COMMERCIAL)
        {
            $commercialGFANumber = $scenario->commercialGFANumber + $scenario->residentialGFANumber;
            $commercialGFAPercentage = $scenario->gfa == 0 ? 0 : ($commercialGFANumber / $scenario->gfa) * 100;
                        
            $scenario->residentialGFANumber = 0;
            $scenario->residentialGFAPercentage = 0;
            $scenario->commercialGFANumber = $commercialGFANumber;
            $scenario->commercialGFAPercentage = $commercialGFAPercentage;
            
            $scenario->save();

            
            
            $commercialNFANumber = $scenario->commercialNFANumber + $scenario->residentialNFANumber;
            $commercialNFAPercentage = $commercialGFANumber == 0 ? 0 : ($commercialNFANumber / $commercialGFANumber) * 100;
    
            $scenario->residentialNFANumber = 0;
            $scenario->residentialNFAPercentage = 0;
            $scenario->commercialNFANumber = $commercialNFANumber;
            $scenario->commercialNFAPercentage = $commercialNFAPercentage;


            //FIXME: should all of the changes above emit their corresponding events?
            // event(new \App\Events\ResidentialGFANumberUpdated($scenario));
        }
    
        if ($scenario->endUse == Scenario::END_USES_RESIDENTIAL)
        {
            $residentialGFANumber = $scenario->commercialGFANumber + $scenario->residentialGFANumber;
            $residentialGFAPercentage = $scenario->gfa == 0 ? 0 : ($residentialGFANumber / $scenario->gfa) * 100;
            
            $scenario->commercialGFANumber = 0;
            $scenario->commercialGFAPercentage = 0;
            $scenario->residentialGFANumber = $residentialGFANumber;
            $scenario->residentialGFAPercentage = $residentialGFAPercentage;


            $residentialNFANumber = $scenario->commercialNFANumber + $scenario->residentialNFANumber;
            $residentialNFAPercentage = $scenario->residentialGFANumber == 0 ? 0 : ($scenario->residentialNFANumber / $scenario->residentialGFANumber) * 100;

            $scenario->commercialNFANumber = 0;
            $scenario->commercialNFAPercentage = 0;
            $scenario->residentialNFANumber = $residentialNFANumber;
            $scenario->residentialNFAPercentage = $residentialNFAPercentage;

            $scenario->save();

            //FIXME: SAME AS ABOVE: should all of the changes above emit their corresponding events?
            // event(new \App\Events\ResidentialGFANumberUpdated($scenario));
        }
    }
}
