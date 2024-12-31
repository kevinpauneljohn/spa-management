<?php

namespace App\Providers;

use App\View\Components\forms\Spa;
use App\View\Components\forms\TherapistForm;
use App\View\Components\Inventory\InventoryCategory;
use App\View\Components\Inventory\InventoryCategoryForm;
use App\View\Components\Inventory\InventoryForm;
use App\View\Components\Inventory\InventoryManagement;
use App\View\Components\PointOfSale\Transactions\SalesTransactionClientsTable;
use App\View\Components\Service\Service;
use App\View\Components\TherapistProfile;
use App\View\Components\Therapists;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('add-spa-form-modal',Spa::class);
        Blade::component('therapist-form',TherapistForm::class);
        Blade::component('therapist-profile',TherapistProfile::class);
        Blade::component('sales-transaction-table',SalesTransactionClientsTable::class);
    }
}
