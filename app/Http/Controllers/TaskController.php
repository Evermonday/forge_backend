<?php

namespace App\Http\Controllers;

use App\Models\Scenario;
use App\Models\Task;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Scenario $scenario)
    {
        return $scenario->tasks->load('predecessors');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }

}
