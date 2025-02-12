<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Models\Task;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Scenario $scenario)
    {
        $scenario->tasks->load('predecessors');
        $tasksArray = $scenario->tasks->toArray();

        $reducedTasks = collect($tasksArray)->map(function($task){
            $preds = [];
            if (array_key_exists('predecessors', $task))
            {
                $preds = collect($task['predecessors'])
                    ->map(fn ($pred) => $pred['id']);
            }
            
            return array_merge($task, ['predecessors' => $preds]);
        });

        return $reducedTasks;
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the given task's name in storage.
     */
    public function updateTaskName(Request $request, Task $task)
    {
        $validated = $request->validate([ 'name' => 'required|string' ]);

        $task->name = $validated['name'];
        $task->save();
    }

    /**
     * Update the given task's mode in storage.
     */
    public function updateTaskMode(Request $request, Task $task)
    {
        // $validated = $request->validate([ 'mode' => ['required', Rule::enum(TASK_MODE::class) ]]);

        $task->mode = $request->mode;
        $task->save();
    }

    /**
     * Update the given task's startDate in storage.
     */
    public function updateTaskStartDate(Request $request, Task $task)
    {
        // $validated = $request->validate([ 'mode' => ['required', Rule::enum(TASK_MODE::class) ]]);

        $task->startDate = $request->startDate;
        $task->save();
        return;
    }

    /**
     * Update the given task's duration in storage.
     */
    public function updateTaskDuration(Request $request, Task $task)
    {
        $validated = $request->validate([ 'duration' => 'required|integer|max:120' ]);

        $task->duration = $validated['duration'];
        $task->save();
        return;
    }

    /**
     * Update the given task's startDate in storage.
     */
    public function updateTaskPredecessor(Request $request, Task $task)
    {
        // $validated = $request->validate([ 'mode' => ['required', Rule::enum(TASK_MODE::class) ]]);

        //TODO: ensure that all task Ids belong to a scenario that the user has
        $allScenarioTasks = $task->scenario->tasks;
        $requestedPredIds = collect($request->predecessors);

        if (count($requestedPredIds) == 1 && $requestedPredIds[0] == 0)
        {
            $task->predecessors()->detach();
            return $task;
        }

        $newlyUnassociatedTaskIds = collect($task->predecessors->map(fn($pred) => $pred->id))->diff($request->predecessors);
        $newlyUnassociatedTaskIds->map(function ($predId) use ($task)
        {
            $task->predecessors()->detach($predId);
        });

        $newlyAssoicatedTaskIds = $requestedPredIds->diff($task->predecessors->map(fn($pred) => $pred->id));

        try
        {
            $requestedPredIds->map(function($predId) use ($allScenarioTasks)
            {
                $allScenarioTasks->firstOrFail(fn($scenarioTask) => $scenarioTask->id == $predId);
            });
        }
        catch(\Illuminate\Support\ItemNotFoundException $e)
        {
            // Validation
            throw new Exception(('Invalid Task Id'));
        }

        
        $newlyAssoicatedTaskIds->map(function ($predId) use ($task)
        {
            
            if($task->checkCyclicalRelationship($predId))
            {
                throw new Exception("Cylical Task Relations Detected.");
            }

            $pred = Task::find($predId);
            $task->predecessors()->save($pred);
        });


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

}
