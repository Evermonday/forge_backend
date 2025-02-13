<?php

namespace App\Models;

use App\Enums\TaskModeEnum;
use App\Exceptions\CyclicalTaskRelation;
use App\Exceptions\IncompatibleTaskMode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use phpDocumentor\Reflection\Types\Boolean;

// TODO: write accessor/mutator for mode and check for preds. If has preds are present, mode can't be AUTO

class Task extends Model
{
    const MODES = ['Auto', 'Manual'];
    const MODE_AUTO = 'Auto';
    const MODE_MANUAL = 'Manual';


    const DEFAULT_NAME = 'New Task';
    const DEFAULT_DURATION = 1;
    const DEFAULT_MODE = TaskModeEnum::AUTO->value;

    protected $appends = array('mode', 'startDate', 'endDate');


    /**
     * Factory Method
     * 
     */
    public static function make(
        Scenario $scenario,
        string $name,
        int $duration,
        int $displayId,
        string $mode = TaskModeEnum::AUTO->value
    ) : Task
    {
        $task = new Task();
        $task->name = $name;
        $task->mode = $mode;
        $task->duration = $duration;
        $task->displayId = $displayId;
        $task->scenario_id = $scenario->id;
        $task->user_id = $scenario->user_id;

        // TODO: Move some of the checks that
        // appen in model's method to here
        $task->startDate = null;

        $task->save();

        return $task;
    }

    /**
    * Get the scenario that this task belongs to.
    */
    public function scenario(): BelongsTo
    {
        return $this->belongsTo(Scenario::class);
    }

    /**
    * Get the immediate predecessors tasks of this task.
    */
    public function predecessors(): BelongsToMany
    {
        return $this
            ->belongsToMany(Task::class, 'task_relations', 'task_id', 'predecessor_id', 'id')
            ->withPivot('type');
    }

    /**
    * Get the immediate successors tasks of this task.
    */
    public function successors(): BelongsToMany
    {
        return $this
            ->belongsToMany(Task::class, 'task_relations', 'predecessor_id', 'task_id', 'id')
            ->withPivot('type');
    }

    /**
     * Get (compute) this task's endDate
     * 
     * ->startOfMonth() is solely precautionary
     */
    protected function getEndDateAttribute()
    {
        return (new \Carbon\Carbon($this->startDate))
            ->startOfMonth()
            ->addMonths($this->duration)
            ->subDay()
            ->toDateString();
    }

    protected function getStartDateAttribute()
    {
        if(!array_key_exists('mode', $this->attributes)
            || !array_key_exists('startDate', $this->attributes))
        {
            return null;
        }
        elseif ($this->attributes['mode'] === TaskModeEnum::MANUAL->value)
        {
            return $this->attributes['startDate'];
        }
        // elseif (count($this->predecessors) === 0
        //     && !is_null($this->attributes['startDate']))
        // {
        //     return $this->attributes['startDate'];
        // }
        elseif (count($this->predecessors) === 0
            && is_null($this->attributes['startDate']))
        {
            return $this->scenario->startDate;
        }
        else
        {
            $outLastingEndDate = $this->getOutlastingPredDate();
            $startDate = (new \Carbon\Carbon($outLastingEndDate))
                ->addDay()
                ->toDateString();

            return $startDate;
        }

    }

    protected function setStartDateAttribute($value)
    {
        if(!array_key_exists('mode', $this->attributes)
            || $this->attributes['mode'] === TaskModeEnum::MANUAL->value)
        {
            $this->attributes['startDate'] = $value;
            // return $this->attributes['startDate'];
        // }
        // elseif ($this->attributes['mode'] === TaskModeEnum::MANUAL->value)
        // {
        //     $startDate = (new \Carbon\Carbon($value))
        //         ->firstOfMonth()
        //         ->toDateString();
        
        //     $this->attributes['startDate'] = $startDate;
        }
        // No need to check for mode because whete
        elseif (is_null($value)
        )
            // && count($this->predecessors) === 0)
        {
            $this->attributes['startDate'] = null;
            // An Auto Task with no preds uses
            // it's scenario's startDate
            // $this->attributes['startDate'] = $this->scenario->startDate;
                        
            //FIXME: for now, just accept the input
            // $startDate = (new \Carbon\Carbon($value))
            //     ->firstOfMonth()
            //     ->toDateString();
        
            // $startDate = null;
            // $this->attributes['startDate'] = $startDate;

        } else {
            throw new IncompatibleTaskMode();
        }
    }

    protected function setModeAttribute($value)
    {
        if ($value === TaskModeEnum::MANUAL->value)
        {
            $this->attributes['mode'] = $value;
            if(count($this->predecessors) === 0)
            {
                $this->predecessors()->detach();
            }
        }
        else
        {
            // MODE_AUTO
            $this->attributes['mode'] = $value;
            $this->attributes['startDate'] = null;
        }
    }

    protected function getModeAttribute()
    {
        return $this->attributes['mode'];
    }

    // protected function getPredecessorsAttribute()
    // {
    //     return $this->attributes['predecessors'];
    // }

    public function savePredecessor(Task $task)
    {
        $isCyclical = $this->checkCyclicalRelationship($task->id);

        if ($isCyclical)
        {
            throw new CyclicalTaskRelation();
        }

        return $this->predecessors()->save($task);
    }

    /**
     * Find the endDate of the outlasting task among the
     * immediate predecessors. If this task doesn't
     * have predecessors, return its own startDate
     */
    public function getOutlastingPredDate() : string
    {
        if ($this->mode != TaskModeEnum::AUTO->value) {
            throw new IncompatibleTaskMode();
        }

        $preds = $this->predecessors;
        if (count($preds) === 0) {
            return $this->attributes['startDate'];
        }

        $outlastingRelative = $preds
            ->sortByDesc(fn($pred) => strtotime($pred->endDate))
            ->first();

        return $outlastingRelative->endDate;
    }

    /**
     * Recurivesly go through all the extended successors
     * of this task and check whether this task identified
     * by this id is in them.
     */
    public function checkCyclicalRelationship($targetId): Bool
    {
        if($targetId === $this->id)
        {
            return true;
        }

        $searchResult = "Not Found";
        $sucs = $this->successors;

        foreach ($sucs as $suc)
        {
            if ($suc->mode == TaskModeEnum::AUTO->value)
            {
                $searchResult = $this->lookForTargetInSucsRecursively($suc, $targetId);
                if ($searchResult === 'Found')
                {
                    return true;
                }
            }
            if ($suc->id === $targetId){
                return true;
            }
        }
        return $searchResult === 'Found';
    }

    public function lookForTargetInSucsRecursively(Task $task, int $targetId): string
    {
        $searchResult = "Not Found";

        $sucs = $task->successors;
        foreach ($sucs as $suc)
        {
            if ($suc->mode == TaskModeEnum::AUTO->value)
            {
                $searchResult = $this->lookForTargetInSucsRecursively($suc, $targetId);
                if ($searchResult === 'Found')
                {
                    return $searchResult;
                }
            }
            if ($suc->id === $targetId){
                return 'Found';
            }
        }

        return $searchResult;
    }

}
