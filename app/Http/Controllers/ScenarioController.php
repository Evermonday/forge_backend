<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $scenario->fsi = 0;
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
        $scenario->user_id = Auth::user()->id;

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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Scenario $scenario)
    {
        $scenario->name = $request->name;
        $scenario->note = $request->note;
        $scenario->tag_id = $request->tag_id;
        $scenario->developmentType = $request->developmentType;
        $scenario->developmentStrategy = $request->developmentStrategy;
        $scenario->unitType = $request->unitType;
        $scenario->endUse = $request->endUse;
        $scenario->fsi = $request->fsi;
        $scenario->gfa = $request->gfa;
        $scenario->gfaCalcMethod = $request->gfaCalcMethod;
        $scenario->areaAllocMethod = $request->areaAllocMethod;
        $scenario->residentialGFANumber = $request->residentialGFANumber;
        $scenario->residentialGFAPercentage = $request->residentialGFAPercentage;
        $scenario->commercialGFANumber = $request->commercialGFANumber;
        $scenario->commercialGFAPercentage = $request->commercialGFAPercentage;
        $scenario->nfaAreaAllocMethod = $request->nfaAreaAllocMethod;
        $scenario->residentialNFANumber = $request->residentialNFANumber;
        $scenario->residentialNFAPercentage = $request->residentialNFAPercentage;
        $scenario->commercialNFANumber = $request->commercialNFANumber;
        $scenario->commercialNFAPercentage = $request->commercialNFAPercentage;

        $scenario->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scenario $scenario)
    {
        //
    }
}
