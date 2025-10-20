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
        Event::listen(BuildingMenu::class, function (BuildingMenu $event){

            if(!auth()->user()->hasRole('super admin'))
            {
                $event->menu->add(
                    [
                        'text' => 'Spa Management',
                        'route'  => 'owner.my.spas',
                        'icon' => 'fas fa-fw fa-spa',
                        'can' => 'view spa',
                        'key' => 'spa',
                    ]
                );
            }
            if(!auth()->user()->hasRole('super admin'))
            {
                $event->menu->add(
                    [
                        'text' => 'Staff Management',
                        'route'  => 'owner.my.staffs',
                        'icon' => 'fas fa-fw fa-user-plus',
                        'can' => 'view staff',
                        'key' => 'staffs',
                    ]
                );
            }
            if(!auth()->user()->hasRole('super admin'))
            {
                $event->menu->add(
                    [
                        'text' => 'Sales Report',
                        'route'  => 'spa.sales.report',
                        'icon' => 'fas fa-fw fa-search-dollar',
                        'can' => 'view sales management',
                        'key' => 'sales_report',
                    ]
                );
            }
            if(!auth()->user()->hasRole('super admin'))
            {
                $event->menu->add(
                    [
                        'text' => 'Discounts',
                        'route'  => 'discounts.index',
                        'icon' => 'fas fa-fw fa-tags',
                        'can' => 'access discounts',
                        'key' => 'discounts',
                    ]
                );
            }
            if(!auth()->user()->hasRole('super admin'))
            {
                $event->menu->add(
                    [
                        'text' => 'Client Management',
                        'route'  => 'clients.index',
                        'icon' => 'fa fa-users',
                        'can' => 'view client',
                        'key' => 'client',
                    ]
                );
            }
//            if(!auth()->user()->hasRole(['admin'])){
            if(!auth()->user()->hasRole(['super admin'])){
                $event->menu->add([
                    'text' => 'Inventory Management',
                    'icon' => 'fas fa-fw fa-shopping-cart',
                    'can' => 'view inventory',
                    'key' => 'inventory',
                    'submenu' => [
                        [
                            'text'    => 'Categories',
                            'shift'   => 'ml-3',
                            'route'  => 'inventory-categories.index',
                            'icon'  => 'fas fa-angle-right',
                            'can' => 'view category'
                        ],
                        [
                            'text'    => 'Inventories',
                            'shift'   => 'ml-3',
                            'route'  => 'inventories.index',
                            'icon'  => 'fas fa-angle-right',
                        ],
                    ],
                ]);
            }
            if(!auth()->user()->hasRole('super admin'))
            {
                $event->menu->add(
                    [
                        'text' => 'Payroll Management',
                        'route'  => 'payroll.index',
                        'icon' => 'fas fa-fw fa-money-check-alt',
                        'can' => 'view payroll',
                        'key' => 'payroll'
                    ]
                );
            }
            if(!auth()->user()->hasRole('super admin'))
            {
                $event->menu->add(
                    [
                        'text' => 'Shift',
                        'route'  => 'sales-shift.index',
                        'icon' => 'fa fa-hourglass',
                        'can' => 'view sales shift',
                        'key' => 'shift'
                    ]
                );
            }
//            if(!auth()->user()->hasRole('super admin'))
//            {
//                $event->menu->add(
//                    [
//                        'text' => 'HR Management',
//                        'icon' => 'fas fa-fw fa-book',
//                        'can' => 'view department',
//                        'key' => 'hr',
//                        'submenu' => [
//                            [
//                                'text'    => 'Departments',
//                                'shift'   => 'ml-2',
//                                'route'  => 'departments.index',
//                                'icon'  => 'fas fa-angle-right',
//                                'can' => 'view department'
//                            ],
//                            [
//                                'text'    => 'Schedules',
//                                'shift'   => 'ml-2',
//                                'route'  => 'schedules.index',
//                                'icon'  => 'fas fa-angle-right',
//                                'can' => 'view schedule'
//                            ],
//                            [
//                                'text'    => 'Employees',
//                                'shift'   => 'ml-2',
//                                'route'  => 'employees.index',
//                                'icon'  => 'fas fa-angle-right',
//                                'can' => 'view employee'
//                            ],
//                            [
//                                'text'    => 'Attendance',
//                                'shift'   => 'ml-2',
//                                'route'  => 'attendances.index',
//                                'icon'  => 'fas fa-angle-right',
//                                'can' => 'view attendance'
//                            ],
//                            [
//                                'text'    => 'Payroll',
//                                'shift'   => 'ml-2',
//                                'route'  => 'employees-payroll',
//                                'icon'  => 'fas fa-angle-right',
//                                'can' => 'view payroll'
//                            ],
//                        ],
//                    ]
//                );
//            }
//            if(!auth()->user()->hasRole(['owner','admin']))
            if(!auth()->user()->hasRole(['owner','admin','super admin']))
            {
                $event->menu->add([
                    'text' => 'Expense Management',
                    'route'  => 'expenses.index',
                    'icon' => 'fas fa-fw fa-chart-pie',
                    'can' => 'view expenses'
                ]);
            }

            if(auth()->user()->hasRole('super admin'))
            {
                $event->menu->add(
                    [
                        'text' => 'Owners',
                        'route'  => 'owners.index',
                        'icon' => 'fas fa-fw fa-user-plus',
                        'can'  => 'view owner',
                    ],
                );
                $event->menu->add('Roles & Permissions',
                    [
                        'text' => 'Roles',
                        'route'  => 'role.index',
                        'icon' => 'fas fa-fw fa-user-md',
                        'can'  => 'view role',
                    ],
                    [
                        'text' => 'Permissions',
                        'route'  => 'permission.index',
                        'icon' => 'fas fa-fw fa-key',
                        'can'  => 'view permission',
                    ],
                );
            }

        });
    }
}
