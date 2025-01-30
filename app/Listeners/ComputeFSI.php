<?php

namespace App\Listeners;

use App\Events\FSIUpdated;
use App\Events\GFAUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ComputeFSI
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
    public function handle(GFAUpdated $event): void
    {
        $scenario = $event->scenario;
        // Only calculate FSI if method is manual
        if($scenario->gfaCalcMethod != Scenario::GFA_CALC_METHOD_MANUAL)
        {
            return;
        }

        $project = $scenario->project;
        $fsi = $project->landArea == 0 ? 0 : $scenario->gfa / $project->landArea;
        // Convert to two decimal places per validation rule
        $fsi = number_format((float)$fsi, 2, '.', '');
        $scenario->fsi = $fsi;
        $scenario->save();

        event(new FSIUpdated($scenario));
    }
}
