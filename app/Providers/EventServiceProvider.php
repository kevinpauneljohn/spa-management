<?php

namespace App\Providers;

use App\Events\EmployeeCreated;
use App\Events\UserCreated;
use App\Listeners\CreateUserEmployee;
use App\Listeners\EmployeeCreatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

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
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
//        Event::listen(BuildingMenu::class, function (BuildingMenu $event){
//            if(!auth()->user()->hasRole('owner'))
//            {
//                $event->menu->addAfter('inventory',[
//                    'text' => 'Expense Management',
//                    'route'  => 'expenses.index',
//                    'icon' => 'fas fa-fw fa-chart-pie',
//                    'can' => 'view expenses'
//                ]);
//            }
//
//        });
    }
}
