<?php

namespace Modules\Sales\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        \Modules\Sales\Events\V1\OrderUpdated::class => [
            \Modules\Sales\Listeners\V1\DecrementProductQuantity::class,
        ],
        \Modules\Sales\Events\V1\OrderCreated::class => [
            \Modules\Sales\Listeners\V1\DecrementProductQuantity::class,
        ],
    ];
    

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
