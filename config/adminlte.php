<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'TKLS POS',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>TKLS</b> POS',
    'logo_img' => 'vendor/adminlte/dist/img/plant.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-default',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => true,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-olive elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-olive navbar-light ',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => true,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => false,
//    'register_url' => 'register',
//    'password_reset_url' => 'password/reset',
    'password_reset_url' => false,
    'password_email_url' => false,
//    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'darkmode-widget',
            'topnav_right' => false,

        ],
        [
            'type'         => 'navbar-notification',
            'id'           => 'my-notification',      // An ID attribute (required).
            'icon'         => 'fas fa-bell',          // A font awesome icon (required).
            'icon_color'   => 'warning',              // The initial icon color (optional).
            'label'        => 0,                      // The initial label for the badge (optional).
            'label_color'  => 'danger',               // The initial badge color (optional).
            'url'          => 'notifications/show',   // The url to access all notifications/elements (required).
            'topnav_right' => true,                   // Or "topnav => true" to place on the left (required).
            'dropdown_mode'   => true,                // Enables the dropdown mode (optional).
            'dropdown_flabel' => 'All notifications', // The label for the dropdown footer link (optional).
//            'update_cfg'   => [
//                'url' => 'notifications/get',         // The url to periodically fetch new data (optional).
//                'period' => 30,                       // The update period for get new data (in seconds, optional).
//            ],
        ],

        // Sidebar items:
//        [
//            'type' => 'sidebar-menu-search',
//            'text' => 'search',
//        ],
//        [
//            'text' => 'blog',
//            'url'  => 'admin/blog',
//            'can'  => 'manage-blog',
//        ],
//        [
//            'text'        => 'pages',
//            'url'         => 'admin/pages',
//            'icon'        => 'far fa-fw fa-file',
//            'label'       => 4,
//            'label_color' => 'success',
//        ],
//        ['header' => 'account_settings'],
        [
            'text' => 'Dashboard',
            'route'  => 'dashboard',
            'icon' => 'fas fa-fw fa-tachometer-alt',
//            'can' => 'view owner'
        ],
        [
            'text' => 'Staff Management',
            'route'  => 'owner.my.staffs',
            'icon' => 'fas fa-fw fa-user-plus',
            'can' => 'view staff'
        ],
        [
            'text' => 'Spa Management',
            'route'  => 'owner.my.spas',
            'icon' => 'fas fa-fw fa-spa',
            'can' => 'view spa'
        ],
        [
            'text' => 'Sales Report',
            'route'  => 'spa.sales.report',
            'icon' => 'fas fa-fw fa-search-dollar',
            'can' => 'view sales management'
        ],
        [
            'text' => 'Discounts',
            'route'  => 'discounts.index',
            'icon' => 'fas fa-fw fa-search-dollar',
            'can' => 'access discounts'
        ],
        [
            'text' => 'Client Management',
            'route'  => 'clients.index',
            'icon' => 'fa fa-users',
            'can' => 'view client'
        ],
        [
            'text' => 'Payroll Management',
            'route'  => 'payroll.index',
            'icon' => 'fas fa-fw fa-money-check-alt',
            'can' => 'view payroll'
        ],
        [
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
        ],
//        [
//            'text' => 'Expense Management',
//            'route'  => 'expenses.index',
//            'icon' => 'fas fa-fw fa-chart-pie',
//            'can' => 'view expenses'
//        ],
        [
            'text' => 'Product Management',
            'url'  => '#',
            'icon' => 'fas fa-fw fa-store',
            'can' => 'view product'
        ],
        [
            'text' => 'Activity Logs',
            'url'  => '#',
            'icon' => 'fa fa-list-ol',
            'can' => 'view activity'
        ],
        [
            'text' => 'Shift',
            'route'  => 'sales-shift.index',
            'icon' => 'fa fa-hourglass',
            'can' => 'view sales shift'
        ],
        [
            'text' => 'HR Management',
            'icon' => 'fas fa-fw fa-book',
            'can' => 'view department',
            'submenu' => [
                [
                    'text'    => 'Departments',
                    'shift'   => 'ml-2',
                    'route'  => 'departments.index',
                    'icon'  => 'fas fa-angle-right',
                    'can' => 'view department'
                ],
                [
                    'text'    => 'Schedules',
                    'shift'   => 'ml-2',
                    'route'  => 'schedules.index',
                    'icon'  => 'fas fa-angle-right',
                    'can' => 'view schedule'
                ],
                [
                    'text'    => 'Employees',
                    'shift'   => 'ml-2',
                    'route'  => 'employees.index',
                    'icon'  => 'fas fa-angle-right',
                    'can' => 'view employee'
                ],
                [
                    'text'    => 'Biometrics',
                    'shift'   => 'ml-2',
                    'icon'  => 'fas fa-angle-right',
                    'can' => 'view biometrics',
                    'submenu' => [
                        [
                            'text'    => 'Attendances',
                            'shift'   => 'ml-3',
                            'route'  => 'biometrics-attendance',
                            'icon'  => 'fas fa-angle-right',
                            'can' => 'view biometrics'
                        ]
                    ]
                ],
            ],

        ],
//        [
//            'text' => 'Payroll Management',
//            'icon' => 'fas fa-fw  fa-money-check-alt',
//
//            'submenu' => [
//                [
//                    'text' => 'Employee Payroll',
//                    'route'  => 'payroll.index',
//                    'icon' => 'fas fa-fw  fa-money-check-alt',
//
//                ],
//                [
//                    'text' => 'Employee Rate',
//                    'route'  => 'setting.index',
//                    'icon' => 'fas fa-fw fa-store',
//                ],
//            ],
//
//            // 'can' => 'view payroll',
//        ],
//        [
//            'text' => 'Attendance',
//            'icon' => 'fas fa-fw fa-store',
//
//            'submenu' => [
//                [
//                    'text' => 'Employee Attendance',
//                    'route'  => 'attendance.index',
//                    'icon' => 'fas fa-fw fa-calendar',
//
//                ],
//                [
//                    'text' => 'Shift Management',
//                    'route'  => 'shift.index',
//                    'icon' => 'fas fa-fw fa-store',
//                ],
//            ],
//
//        ],
        [
            'text'    => 'Settings',
            'icon'    => 'fas fa-cogs',
            'can'     => 'view settings',
            'submenu' => [
                [
                    'text' => 'Roles',
                    'route'  => 'role.index',
                    'can'  => 'view role',
                ],
                [
                    'text' => 'Permissions',
                    'route'  => 'permission.index',
                    'can'  => 'view permission',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'BsStepper' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css',
//                    'location' => 'vendor/bs-stepper/css/bs-stepper.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/bs-stepper/dist/js/bs-stepper.min.js',
//                    'location' => 'vendor/bs-stepper/js/bs-stepper.min.js',
                ],
            ],

        ],
        'DatePicker' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js',
                ],
            ],
        ],
        'Toastr' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/toastr/toastr.min.js',
                ],
            ],
        ],
        'ClearErrors' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/clear_errors.js',
                ],
            ],
        ],
        'CustomAlert' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/alerts.js',
                ],
            ],
        ],
        'Therapist' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'therapist/css/therapist.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'therapist/js/therapist.js',
                ],
            ],
        ],
        'CustomCSS' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/style.css',
                ],
            ],
        ],

        'Moment' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/moment/moment.min.js',
                ],
            ],
        ],
        'BootstrapDatePicker' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js',
                ],
            ],
        ],
        'InputMask' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/inputmask/jquery.inputmask.min.js',
                ],
            ]
        ],
        'IcheckBootstrap' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/icheck-bootstrap/icheck-bootstrap.min.css',
                ],
            ],
        ],
        'DateRangePicker' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/daterangepicker/daterangepicker.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/daterangepicker/daterangepicker.js',
                ],
            ],
        ],
        'Inventories' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'inventory/js/inventory.js',
                ],
            ],
        ],
        'Categories' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'inventory/js/category.js',
                ],
            ],
        ],
        'tempusdominusBootstrap4' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js',
                ],
            ],
        ],
        'Fullcalendar' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'vendor/fullcalendar/main.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'vendor/fullcalendar/main.min.js',
                ],
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
