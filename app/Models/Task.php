<?php

namespace App\Models;

use App\Exceptions\IncompatibleTaskMode;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use phpDocumentor\Reflection\Types\Boolean;

class Task extends Model
{
    const MODES = ['Auto', 'Manual'];
    const MODE_AUTO = 'Auto';
    const MODE_MANUAL = 'Manual';


    protected $appends = array('endDate');


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
     */
    protected function getEndDateAttribute()
    {
        return (new \Carbon\Carbon($this->startDate))
            ->addMonths($this->duration)
            ->subDay()
            ->toDateString();
    }

    /**
     * Find the outlasting task among the immediate predecessors
     */
    public function getPreds() : Task {
        if ($this->type != self::MODE_AUTO) {
            throw new IncompatibleTaskMode();
        }

        $preds = $this->predecessors()->all();
        $outlastingRelative = $preds[0];

        array_map(fn($pred) => $outlastingRelative = $pred->endDate > $outlastingRelative->endDate ? $pred : $outlastingRelative
        , $preds);

        return $outlastingRelative;
    }

    /**
     * Recurivesly go through all the extended predecessors
     * of thistask and check whether this task identified
     * by this id is in them.
     */
    public function checkCyclicalRelationship(): Bool {
        return false;
    }

}
