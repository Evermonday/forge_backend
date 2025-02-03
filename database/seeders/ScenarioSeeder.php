<?php

namespace Database\Seeders;

use App\Models\Scenario;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $scenario = new Scenario();

            $scenario->name = 'Auto Generated Scenario';
            $scenario->note = '';
            $scenario->tag_id = $user->tags[0]->id;
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

            $scenario->user_id = $user->id;
            $scenario->project_id = $user->project->id;

            $scenario->save();
        }
    }
}
