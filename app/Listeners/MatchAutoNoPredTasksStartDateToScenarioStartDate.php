<?php

namespace App\Listeners;

use App\Events\ScenarioStartDateUpdated;
use App\Events\TaskStartDateUpdated;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MatchAutoNoPredTasksStartDateToScenarioStartDate
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Match the startDate of those tasks of updated scenario that are
     * of mode auto and have no predecessors (i.e. those which
     * are tied to the scenario start date).
     */
    public function handle(ScenarioStartDateUpdated $event): void
    {
        $scenario = $event->scenario;

        $autoNoPredTasks = $scenario
            ->tasks
            ->filter(fn($task) => $task->mode == Task::MODE_AUTO && count($task->predecessors) == 0 );
        
        $autoNoPredTasks->each(function($task) use ($scenario)
        {
            $task->startDate = $scenario->startDate;
            $task->save();

            event(new TaskStartDateUpdated($task));
        });
    }
}
