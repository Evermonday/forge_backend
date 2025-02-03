<?php

namespace Database\Seeders;

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
            $taskA = new Task();

            $taskA->name = 'Task A';
            $taskA->mode = Task::MODE_AUTO;
            $taskA->duration = $duration;
            // $taskA->startDate = (new \Carbon\Carbon())->startOfMonth();
            $taskA->startDate = $scenario->startDate;
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
            $taskB->mode = Task::MODE_AUTO;
            $taskB->duration = $duration;
            $taskB->startDate = (new \Carbon\Carbon($taskA->endDate))
                ->addMonths($taskB->duration)
                ->subDay();
            $taskB->displayId = $displayId;

            $taskB->scenario_id = $scenario->id;
            $taskB->user_id = $scenario->user_id;

            $taskB->save();
            

            $displayId++;
            $duration = rand(1, 8);
            
            $taskB->predecessors()->save($taskA);

            # TASK C
            $taskC = new Task();
            $taskC->name = 'Task C';
            $taskC->mode = Task::MODE_MANUAL;
            $taskC->duration = $duration;
            $taskC->startDate = (new \Carbon\Carbon($scenario->start))
                ->addMonths(rand(3, 7))
                ->subDays(1);
            $taskC->displayId = $displayId;

            $taskC->scenario_id = $scenario->id;
            $taskC->user_id = $scenario->user_id;

            $taskC->save();


            $displayId++;
            $duration = rand(1, 8);
        }
    }
}
