<?php

namespace App\Listeners;

use App\Models\Tag;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserDefaultTags
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
        $event
            ->user
            ->tags()
            ->createMany(Tag::DEFAULT_TAGS);
    }
}
