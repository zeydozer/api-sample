<?php

namespace App\Providers;

use App\Events\Canceled;
use App\Events\Renewed;
use App\Events\Started;
use App\Listeners\CanceledListener;
use App\Listeners\RenewedListener;
use App\Listeners\StartedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Started::class => [
            StartedListener::class,
        ],
        Renewed::class => [
            RenewedListener::class,
        ],
        Canceled::class => [
            CanceledListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}