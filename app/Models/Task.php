<?php

namespace App\Models;

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


    protected $appends = array('mode', 'startDate', 'endDate');



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
            return (new \Carbon\Carbon())
                ->addDay()
                ->toDateString();
        }

        if ($this->attributes['mode'] === self::MODE_MANUAL)
        {
            return $this->attributes['startDate'];
        }
        elseif (count($this->predecessors) === 0
            && !is_null($this->attributes['startDate'])) {
            return $this->attributes['startDate'];
        }
        elseif (count($this->predecessors) === 0
            && is_null($this->attributes['startDate'])) {
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
        if(!array_key_exists('mode', $this->attributes))
        {
            $this->attributes['startDate'] = (new \Carbon\Carbon())
                ->addDay()
                ->toDateString();
            return;
            // return $this->attributes['startDate'];
        }

        if ($this->attributes['mode'] === self::MODE_MANUAL)
        {
            $startDate = (new \Carbon\Carbon($value))
                ->firstOfMonth()
                ->toDateString();
        
            $this->attributes['startDate'] = $startDate;
        }
        elseif ($this->attributes['mode'] === self::MODE_AUTO
            && count($this->predecessors) === 0)
        {
            // An Auto Task with no preds uses
            // it's scenario's startDate
            // $this->attributes['startDate'] = $this->scenario->startDate;
                        
            //FIXME: for now, just accept the input
            $startDate = (new \Carbon\Carbon($value))
                ->firstOfMonth()
                ->toDateString();
        
            $this->attributes['startDate'] = $startDate;

        } else {

            // if is AUTO and has preds, startDate can't be set
            throw new IncompatibleTaskMode();
        }
    }

    protected function setModeAttribute($value)
    {
        if ($value === self::MODE_MANUAL
            && count($this->predecessors) !== 0)
        {
            throw new IncompatibleTaskMode();
        }
        else
        {
            // MODE_AUTO
            $this->attributes['mode'] = $value;
            $this->startDate = null;
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
        if ($this->mode != self::MODE_AUTO) {
            throw new IncompatibleTaskMode();
        }

        $preds = $this->predecessors;
        if (count($preds) === 0) {
            return $this->attributes['startDate'];
        }

        $outlastingRelative = $preds->sort(fn($pred) => $pred->endDate)[0];

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
            if ($suc->mode == self::MODE_AUTO)
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
            if ($suc->mode == self::MODE_AUTO)
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
