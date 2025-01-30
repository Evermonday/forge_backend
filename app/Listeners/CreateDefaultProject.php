<?php

namespace App\Listeners;

use App\Models\Project;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateDefaultProject
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $defaultProject = new Project();
        
        $event
            ->user
            ->project()
            ->save($defaultProject);

    }
}
