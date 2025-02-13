<?php

namespace App\Http\Controllers;

use App\Events\AreaAllocMethodUpdated;
use App\Events\CommercialGFANumberUpdated;
use App\Events\CommercialGFAPercentageUpdated;
use App\Events\CommercialNFANumberUpdated;
use App\Events\CommercialNFAPercentageUpdated;
use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Events\DevelopmentTypeUpdated;
use App\Events\EndUseUpdated;
use App\Events\FSIUpdated;
use App\Events\GFACalcMethodUpdated;
use App\Events\GFAUpdated;
use App\Events\NFAAreaAllocMethodUpdated;
use App\Events\ScenarioStartDateUpdated;
use App\Events\ResidentialGFANumberUpdated;
use App\Events\ResidentialGFAPercentageUpdated;
use App\Events\ResidentialNFANumberUpdated;
use App\Events\ResidentialNFAPercentageUpdated;
use App\Events\UnitTypeUpdated;
use App\Models\Task;
use Illuminate\Validation\ValidationException;

class ScenarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Auth::user()->scenarios;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function fromScratch()
    {
        $scenario = new Scenario();
        $scenario->name = 'New Scenario';
        $scenario->note = '';
        $scenario->tag_id = Auth::user()->tags[0]->id;
        $scenario->developmentType = Scenario::DEVELOPMENT_TYPES[0];
        $scenario->developmentStrategy = Scenario::DEVELOPMENT_STRATEGIES[0];
        $scenario->unitType = Scenario::UNIT_TYPES[0];
        $scenario->endUse = Scenario::END_USES[0];
        $scenario->fsi = 1;
        $scenario->gfa = 0;
        $scenario->gfaCalcMethod = Scenario::GFA_CALC_METHODS[0];
        $scenario->areaAllocMethod = Scenario::AREA_ALLOC_METHODS[0];
        $scenario->residentialGFANumber = 0;
        $scenario->residentialGFAPercentage = 0;
        $scenario->commercialGFANumber = 0;
        $scenario->commercialGFAPercentage = 0;
        $scenario->nfaAreaAllocMethod = Scenario::NFA_AREA_ALLOC_METHODS[0];
        $scenario->residentialNFANumber = 0;
        $scenario->residentialNFAPercentage = 0;
        $scenario->commercialNFANumber = 0;
        $scenario->commercialNFAPercentage = 0;
        $scenario->startDate = (new \Carbon\Carbon())->startOfMonth();

        $scenario->user_id = Auth::user()->id;
        $scenario->project_id = Auth::user()->project->id;

        $scenario->save();
        return $scenario;
    }

    /**
     * Display the specified resource.
     */
    public function show(Scenario $scenario)
    {
        $scenario->tag;
        return $scenario;
    }

    //DELETE:
    /**
     * Update the specified resource in storage.
     */
    public function updateStartDate(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'startDate' => 'required|date_format:Y-m-d' ]);

        $scenario->startDate = $validated['startDate'];
        $scenario->save();

        event(new ScenarioStartDateUpdated($scenario));
    }

    /**
     * Update Scenario's name in storage.
     */
    public function updateName(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'name' => 'required|max:25' ]);

        $scenario->name = $validated['name'];
        $scenario->save();
        // event
    }

    /**
     * Update Scenario's note in storage.
     */
    public function updateNote(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'note' => 'required|nullable|max:255' ]);

        $scenario->note = $validated['note'];
        $scenario->save();
        // event
    }

    /**
     * Update Scenario's tag in storage.
     */
    public function updateTag(Request $request, Scenario $scenario)
    {
        // TODO:
        // $validated = $request->validate([ 'tagId' => 'required|exists:' ]);

        $scenario->tag_id = $request->tagId;
        $scenario->save();
        // event
    }

    /**
     * Update Scenario's development type in storage.
     */
    public function updateDevelopmentType(Request $request, Scenario $scenario)
    {
        //TODO:
        // $validated = $request->validate([ 'developmentType' => ['required', Rule::enum(Scenario::DEVELOPMENT_TYPES) ]]);
        // $scenario->developmentType = $validated['developmentType'];
        $scenario->developmentType = $request->developmentType;
        $scenario->save();
        event(new DevelopmentTypeUpdated($scenario));
    }

    /**
     * Update Scenario's development strtegy in storage.
     */
    public function updateDevelopmentStrategy(Request $request, Scenario $scenario)
    {
        //TODO:
        // $validated = $request->validate([ 'developmentStrategy' => ['required', Rule::enum(DEVELOPMENT_STRATEGY::class) ]]);
        // $scenario->developmentStrategy = $validated['developmentStrategy'];
        $scenario->developmentStrategy = $request->developmentStrategy;
        $scenario->save();
        event(new EndUseUpdated($scenario));
    }
    /**
     * Update Scenario's unit type in storage.
     */
    public function updateUnitType(Request $request, Scenario $scenario)
    {
        //TODO:
        // $validated = $request->validate([ 'unitType' => ['required', Rule::enum(UNIT_TYPE::class) ]]);
        // $scenario->unitType = $validated['unitType'];
        $scenario->unitType = $request->unitType;
        $scenario->save();
        event(new UnitTypeUpdated($scenario));
    }

    /**
     * Update Scenario's development type in storage.
     */
    public function updateEndUse(Request $request, Scenario $scenario)
    {
        //TODO:
        // $validated = $request->validate([ 'endUse' => ['required', Rule::enum(END_USE::class) ]]);
        // $scenario->endUse = $validated['endUse'];
        $scenario->endUse = $request->endUse;
        $scenario->save();
        event(new EndUseUpdated($scenario));
    }

    /**
     * Update Scenario's GFA Calc Method in storage.
     */
    public function updateGFACalcMethod(Request $request, Scenario $scenario)
    {
        //TODO:
        // $validated = $request->validate([ 'gfaCalcMethod' => ['required', Rule::enum(GFA_CALC_METHOD::class) ]]);
        // $scenario->gfaCalcMethod = $validated['gfaCalcMethod'];
        $scenario->gfaCalcMethod = $request->gfaCalcMethod;
        $scenario->save();
        event(new GFACalcMethodUpdated($scenario));
    }

    /**
     * Update Scenario's FSI in storage.
     */
    public function updateFSI(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'fsi' => 'required|decimal:2|max:10000' ]);
        #TODO: Rewrite as validation
        if($scenario->gfaCalcMethod != Scenario::GFA_CALC_METHOD_FSI_BASED ) {
            throw ValidationException::withMessages(['fsi' => "Incompatible GFA Calculation Method"]);
        }

        $scenario->fsi = $validated['fsi'];
        $scenario->save();

        event(new FSIUpdated($scenario));
    }

    /**
     * Update Scenario's GFA in storage.
     */
    public function updateGFA(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'gfa' => 'required|integer|max:10000000' ]);
        #TODO: Rewrite as validation
        if($scenario->gfaCalcMethod != Scenario::GFA_CALC_METHOD_MANUAL ) {
            throw ValidationException::withMessages(['fsi' => "Incompatible GFA Calculation Method"]);
        }

        $scenario->gfa = $validated['gfa'];
        $scenario->save();

        event(new GFAUpdated($scenario));
    }

    /**
     * Update Scenario's GFA Area Allocation Method in storage.
     */
    public function areaAllocMethod(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'areaAllocMethod' => 'required' ]);
        // Rule::enum(AREA_ALLOC_METHODS::class)

        $scenario->areaAllocMethod = $validated['areaAllocMethod'];
        $scenario->save();

        event(new AreaAllocMethodUpdated($scenario));
    }

    /**
     * Update Scenario's residential GFA Number in storage.
     */
    public function residentialGFANumber(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'residentialGFANumber' => 'required|integer|max:10000000' ]);
        
        #TODO: Rewrite as validation
        $totalGFA = $request->residentialGFANumber + $scenario->commercialGFANumber;
        $totalGFA = round($totalGFA, 2);

        if($totalGFA > $scenario->gfa)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Must Not Exceed GFA."]);
        }

        #TODO: Rewrite as validation
        if($scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_MANUAL)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Incompatible Area Allocation Method."]);
        }

        $scenario->residentialGFANumber = $validated['residentialGFANumber'];
        $scenario->save();

        event(new ResidentialGFANumberUpdated($scenario));
    }

    /**
     * Update Scenario's residential GFA Percentage in storage.
     */
    public function residentialGFAPercentage(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'residentialGFAPercentage' => 'required|integer|max:100' ]);
        
        #TODO: Rewrite as validation
        $totalGFAPercentage = $request->residentialGFAPercentage + $scenario->commercialGFAPercentage;
        $totalGFAPercentage = round($totalGFAPercentage, 2);

        if ($totalGFAPercentage > 100)
        {
            throw ValidationException::withMessages(['residentialGFAPercentage' => "Total GFA Percentage Must Not Exceed 100%."]);
        }

        #TODO: Rewrite as validation
        if($scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Incompatible Area Allocation Method."]);
        }

        $scenario->residentialGFAPercentage = $validated['residentialGFAPercentage'];
        $scenario->save();

        event(new ResidentialGFAPercentageUpdated($scenario));
    }

    /**
     * Update Scenario's commercial GFA Number in storage.
     */
    public function commercialGFANumber(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'commercialGFANumber' => 'required|integer|max:10000000' ]);
        
        #TODO: Rewrite as validation
        $totalGFA = $request->commercialGFANumber + $scenario->residentialGFANumber;
        $totalGFA = round($totalGFA, 2);

        if($totalGFA > $scenario->gfa)
        {
            throw ValidationException::withMessages(['commercialGFANumber' => "Must Not Exceed GFA."]);
        }

        #TODO: Rewrite as validation
        if($scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_MANUAL)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Incompatible Area Allocation Method."]);
        }

        $scenario->commercialGFANumber = $validated['commercialGFANumber'];
        $scenario->save();

        event(new CommercialGFANumberUpdated($scenario));
    }

    /**
     * Update Scenario's commercial GFA Percentage in storage.
     */
    public function commercialGFAPercentage(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'commercialGFAPercentage' => 'required|integer|max:100' ]);
        
        #TODO: Rewrite as validation
        $totalGFAPercentage = $request->commercialGFAPercentage + $scenario->residentialGFAPercentage;
        $totalGFAPercentage = round($totalGFAPercentage, 2);

        if ($totalGFAPercentage > 100)
        {
            throw ValidationException::withMessages(['commercialGFAPercentage' => "Total GFA Percentage Must Not Exceed 100%."]);
        }

        #TODO: Rewrite as validation
        if($scenario->areaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Incompatible Area Allocation Method."]);
        }

        $scenario->commercialGFAPercentage = $validated['commercialGFAPercentage'];
        $scenario->save();

        event(new CommercialGFAPercentageUpdated($scenario));
    }

    /**
     * Update Scenario's NFA Area Allocation Method in storage.
     */
    public function nfaAreaAllocMethod(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'nfaAreaAllocMethod' => 'required' ]);
        // Rule::enum(AREA_ALLOC_METHODS::class)

        $scenario->nfaAreaAllocMethod = $validated['nfaAreaAllocMethod'];
        $scenario->save();

        event(new NFAAreaAllocMethodUpdated($scenario));
    }

    /**
     * Update Scenario's residential NFA Number in storage.
     */
    public function residentialNFANumber(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'residentialNFANumber' => 'required|integer|max:10000000' ]);
        
        #TODO: Rewrite as validation
        if($request->residentialNFANumber > $scenario->residentialGFANumber)
        {
            throw ValidationException::withMessages(['residentialNFANumber' => "Must Not Exceed Residential GFA."]);
        }

        #TODO: Rewrite as validation
        if($scenario->nfaAreaAllocMethod != Scenario::AREA_ALLOC_METHOD_MANUAL)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Incompatible NFA Area Allocation Method."]);
        }

        $scenario->residentialNFANumber = $validated['residentialNFANumber'];
        $scenario->save();

        event(new ResidentialNFANumberUpdated($scenario));
    }

    /**
     * Update Scenario's residential NFA Percentage in storage.
     */
    public function residentialNFAPercentage(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'residentialNFAPercentage' => 'required|integer|max:100' ]);
        $residentialNFAPercentage = $validated['residentialNFAPercentage'];
        $residentialNFAPercentage = round($residentialNFAPercentage, 2);


        #TODO: Rewrite as validation
        if($scenario->nfaAreaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Incompatible NFA Area Allocation Method."]);
        }

        $scenario->residentialNFAPercentage = $residentialNFAPercentage;
        $scenario->save();

        event(new ResidentialNFAPercentageUpdated($scenario));
    }

    /**
     * Update Scenario's commercial NFA Number in storage.
     */
    public function commercialNFANumber(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'commercialNFANumber' => 'required|integer|max:10000000' ]);
        
        #TODO: Rewrite as validation
        if($request->commercialNFANumber > $scenario->commercialGFANumber)
        {
            throw ValidationException::withMessages(['commercialNFANumber' => "Must Not Exceed Commercial GFA."]);
        }

        #TODO: Rewrite as validation
        if($scenario->nfaAreaAllocMethod != Scenario::AREA_ALLOC_METHOD_MANUAL)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Incompatible NFA Area Allocation Method."]);
        }

        $scenario->commercialNFANumber = $validated['commercialNFANumber'];
        $scenario->save();

        event(new CommercialNFANumberUpdated($scenario));
    }

    /**
     * Update Scenario's commercial NFA Percentage in storage.
     */
    public function commercialNFAPercentage(Request $request, Scenario $scenario)
    {
        $validated = $request->validate([ 'commercialNFAPercentage' => 'required|integer|max:100' ]);
        $commercialNFAPercentage = $validated['commercialNFAPercentage'];
        $commercialNFAPercentage = round($commercialNFAPercentage, 2);

        #TODO: Rewrite as validation
        if($scenario->nfaAreaAllocMethod != Scenario::AREA_ALLOC_METHOD_PERCENTILE)
        {
            throw ValidationException::withMessages(['residentialGFANumber' => "Incompatible NFA Area Allocation Method."]);
        }

        $scenario->commercialNFAPercentage = $commercialNFAPercentage;
        $scenario->save();

        event(new CommercialNFAPercentageUpdated($scenario));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scenario $scenario)
    {
        //
    }

    /**
     * Update all displayIds of the tasks belonging to a given scenario in storage.
     */
    public function updateTaskDisplayIds(Request $request, Scenario $scenario)
    {
        //TODO: validation

        $newTaskDisplayIds = $request->taskIds;
        return $newTaskDisplayIds;
    }

    /**
     * Create a new blank task for the given scenario in storage.
     */
    public function createBlankTask(Request $request, Scenario $scenario)
    {
        // $validated = $request->validate(
        //     [
        //         'name'
        //         'duration' => 'required|integer|max:1000'
        //     ]);
        # Validation
        // if ($request->task->mode == Task::MODE_AUTO
            // && $request->task->predecessor_id !== null)
        // {
            // throw ValidationException::withMessages(['predecessor_id' => "Incompatible Task Mode."]);
        //}

        $tasks = $scenario->tasks;

        // get the largest displayId
        $maxDisplayId = $tasks->max(fn($task) => $task->displayId);
        $maxDisplayId++;
        
        $task = Task::make(
            scenario: $scenario,
            name: Task::DEFAULT_NAME,
            duration: Task::DEFAULT_DURATION,
            displayId: $maxDisplayId
        );
        $task->predecessors;

        return $task;
    }
}
