<?php

namespace Database\Seeders;

use App\Enums\TaskModeEnum;
use App\Models\Scenario;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $scenarios = Scenario::all();

        foreach ($scenarios as $scenario) {
            $displayId = 1;
            $duration = rand(1, 8);

            # TASK A
            #TODO:
            // $taskA = Task::make(
            //     scenario: $scenario,
            //     name: 'Task A',
            //     duration: $duration,
            //     displayId: $displayId

            // );

            $taskA = new Task();
            $taskA->name = 'Task A';
            $taskA->mode = TaskModeEnum::AUTO->value;
            $taskA->duration = $duration;
            // $taskA->startDate = (new \Carbon\Carbon())->startOfMonth();
            // $taskA->startDate = $scenario->startDate;
            // $taskA->endDate = (new \Carbon\Carbon($taskA->startDate))
            //     ->addMonths($taskA->duration)
            //     ->subDay();
            $taskA->displayId = $displayId;

            $taskA->scenario_id = $scenario->id;
            $taskA->user_id = $scenario->user_id;

            $taskA->save();


            $displayId++;
            $duration = rand(1, 8);
            

            # TASK B
            $taskB = new Task();

            $taskB->name = 'Task B';
            $taskB->mode = TaskModeEnum::AUTO->value;
            $taskB->duration = $duration;
            // $taskB->startDate = (new \Carbon\Carbon($taskA->endDate))
            //     ->addMonths($taskB->duration);
                // ->subDay();
            $taskB->displayId = $displayId;

            $taskB->scenario_id = $scenario->id;
            $taskB->user_id = $scenario->user_id;

            $taskB->save();
            

            $displayId++;
            $duration = rand(1, 8);
            
            $taskB->savePredecessor($taskA);

            # TASK C
            $taskC = new Task();
            $taskC->name = 'Task C';
            $taskC->mode = TaskModeEnum::MANUAL->value;
            $taskC->duration = $duration;
            $taskC->startDate = (new \Carbon\Carbon($scenario->start))
                ->startOfMonth()
                ->addMonths(rand(3, 7));
                // ->subDay();
            $taskC->displayId = $displayId;

            $taskC->scenario_id = $scenario->id;
            $taskC->user_id = $scenario->user_id;

            $taskC->save();


            $displayId++;
            $duration = rand(1, 8);
        }
    }
}
