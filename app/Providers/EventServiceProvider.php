<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\Project;
use App\Observers\ProjectObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Project::class => [ProjectObserver::class],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}