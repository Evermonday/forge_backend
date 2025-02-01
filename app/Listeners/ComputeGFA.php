<?php

namespace App\Listeners;

use App\Events\FSIUpdated;
use App\Events\GFAUpdated;
use App\Models\Scenario;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

// If GFA is calculated, it's always based on FSI 
class ComputeGFA
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
    public function handle(FSIUpdated $event): void
    {
        $scenario = $event->scenario;
        $project = $scenario->project;
        $scenario->gfa = $scenario->fsi * $project->landArea;

        $scenario->save();

        event(new GFAUpdated($scenario));
    }
}
