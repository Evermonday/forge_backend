<?php

namespace App\Listeners;

use App\Events\TaskStartDateUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MatchTaskSuccessorsStartDate
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
    public function handle(TaskStartDateUpdated $event): void
    {
        $task = $event->task;

        // $task->successors();
    }
}
