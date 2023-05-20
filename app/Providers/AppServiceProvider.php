<?php

namespace App\Providers;

use App\View\Components\Forms\Spa;
use App\View\Components\forms\TherapistForm;
use App\View\Components\Inventory\InventoryCategory;
use App\View\Components\Inventory\InventoryCategoryForm;
use App\View\Components\Inventory\InventoryForm;
use App\View\Components\Inventory\InventoryManagement;
use App\View\Components\Inventory\UpdateQuantity;
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
        Blade::component('all-therapist-thru-specific-spa',Therapists::class);
        Blade::component('inventory-management',InventoryManagement::class);
        Blade::component('inventory-form',InventoryForm::class);
        Blade::component('inventory-category',InventoryCategory::class);
        Blade::component('inventory-category-form',InventoryCategoryForm::class);
        Blade::component('service',Service::class);

        Blade::component('update-quantity',UpdateQuantity::class);
    }
}
