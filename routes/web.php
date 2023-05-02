<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect(\route('dashboard'));
});

Auth::routes();
Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard',[\App\Http\Controllers\Dashboard\DashboardController::class,'index'])->name('dashboard');
    Route::get('/receptionist-dashboard',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'index'])->name('receptionist.dashboard');
    Route::get('/receptionist-lists',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'lists'])->name('receptionist.lists');
    Route::get('/receptionist-service/{id}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'getServices'])->name('receptionist.service');
    Route::get('/receptionist-plus-range',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'plusTime'])->name('receptionist.plus.range');
    Route::get('/receptionist-room-range/{num}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'roomRange'])->name('receptionist.room.range');
    Route::get('/receptionist-therapist/{id}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'getTherapist'])->name('receptionist.therapist');
    Route::get('/receptionist-reserved/{id}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'getReservedTherapist'])->name('receptionist.reserved');
    Route::post('/create/{id}/{amount}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'store'])->name('receptionist.create');
    Route::put('/update/{id}/{amount}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'update'])->name('receptionist.update');

    Route::get('/client-list',[\App\Http\Controllers\ClientController::class,'getList'])->name('client.lists');
    Route::get('/client/{id}',[\App\Http\Controllers\ClientController::class,'show'])->name('client.show');

    Route::get('/transaction/{id}',[\App\Http\Controllers\TransactionController::class,'show'])->name('transaction.show');
    Route::get('/transaction-list/{id}',[\App\Http\Controllers\TransactionController::class,'lists'])->name('transaction.lists');
    Route::get('/transaction-total-sales/{id}',[\App\Http\Controllers\TransactionController::class,'getTotalSales'])->name('transaction.count');
    Route::get('/transaction-masseur-availability/{id}',[\App\Http\Controllers\TransactionController::class,'getTherapistAvailability'])->name('transaction.availability');
    Route::get('/transaction-latest-reservation/{id}',[\App\Http\Controllers\TransactionController::class,'getLatestReservation'])->name('transaction.latest.reservation');
    Route::get('/transaction-room-availability/{id}',[\App\Http\Controllers\TransactionController::class,'getRoomAvailability'])->name('transaction.room.availability');

    Route::resource('owners',\App\Http\Controllers\Owners\OwnerController::class);
    Route::get('/owners-list',[\App\Http\Controllers\Owners\OwnerController::class,'owner_lists'])->name('owner.lists');
    Route::post('/owners',[\App\Http\Controllers\Owners\OwnerController::class,'store'])->name('owner.store');
    Route::get('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'show'])->name('owner.show');
    Route::put('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'update'])->name('owner.update');
    Route::delete('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'destroy'])->name('owner.delete');

    Route::get('/spa-list/{id}',[\App\Http\Controllers\SpaController::class,'lists'])->name('spa.lists');
    Route::get('/spa/overview/{id}',[\App\Http\Controllers\SpaController::class,'overview'])->name('spa.overview');
    Route::post('/spa',[\App\Http\Controllers\SpaController::class,'store'])->name('spa.store');
    Route::get('/spa/{id}',[\App\Http\Controllers\SpaController::class,'show'])->name('spa.show');
    Route::put('/spa/{id}',[\App\Http\Controllers\SpaController::class,'update'])->name('spa.update');
    Route::delete('/spa/{id}',[\App\Http\Controllers\SpaController::class,'destroy'])->name('spa.delete');

    Route::get('my-spas',[\App\Http\Controllers\SpaController::class,'my_spas'])->name('owner.my.spas');
    Route::get('my-spa-lists',[\App\Http\Controllers\SpaController::class,'get_owner_spas'])->name('owner.list.spas');

    Route::get('/therapist-list/{id}',[\App\Http\Controllers\TherapistController::class,'lists'])->name('therapist.lists');
    Route::get('/therapist/overview/{id}',[\App\Http\Controllers\TherapistController::class,'overview'])->name('therapist.overview');
    Route::post('/therapist',[\App\Http\Controllers\TherapistController::class,'store'])->name('therapist.store');
    Route::get('/therapist/{id}',[\App\Http\Controllers\TherapistController::class,'show'])->name('therapist.show');
    Route::put('/therapist/{id}',[\App\Http\Controllers\TherapistController::class,'update'])->name('therapist.update');
    Route::delete('/therapist/{id}',[\App\Http\Controllers\TherapistController::class,'destroy'])->name('therapist.delete');

    Route::get('/therapists-profile/{id}',[\App\Http\Controllers\TherapistController::class,'therapist_profile'])->name('therapists.profile');

    Route::get('/service-list/{id}',[\App\Http\Controllers\ServiceController::class,'lists'])->name('service.lists');
    Route::get('/service/overview/{id}',[\App\Http\Controllers\ServiceController::class,'overview'])->name('service.overview');
    Route::post('/service',[\App\Http\Controllers\ServiceController::class,'store'])->name('service.store');
    Route::get('/service/{id}',[\App\Http\Controllers\ServiceController::class,'show'])->name('service.show');
    Route::get('/service-duration-range',[\App\Http\Controllers\ServiceController::class,'durationRange'])->name('service.duration.range');
    Route::put('/service/{id}',[\App\Http\Controllers\ServiceController::class,'update'])->name('service.update');
    Route::delete('/service/{id}',[\App\Http\Controllers\ServiceController::class,'destroy'])->name('service.delete');
    Route::get('/service-price/{id}/{spa_id}',[\App\Http\Controllers\ServiceController::class,'servicePricing'])->name('service.price');
    Route::get('/service-plus-time-price/{id}/{spa_id}/{selected_id}',[\App\Http\Controllers\ServiceController::class,'servicePricingPlusTime'])->name('service.price.plustime');

    Route::get('/permission',[\App\Http\Controllers\PermissionController::class,'index'])->name('permission.index');
    Route::get('/permission-list',[\App\Http\Controllers\PermissionController::class,'lists'])->name('permission.list');
    Route::post('/permission',[\App\Http\Controllers\PermissionController::class,'store'])->name('permission.store');
    Route::post('/permission-roles',[\App\Http\Controllers\PermissionController::class,'getPermissionRoles'])->name('permission.roles');
    Route::put('/permission/{id}',[\App\Http\Controllers\PermissionController::class,'update'])->name('permission.update');
    Route::delete('/permission/{id}/{name}',[\App\Http\Controllers\PermissionController::class,'destroy'])->name('permission.delete');

    Route::get('/role',[\App\Http\Controllers\RoleController::class,'index'])->name('role.index');
    Route::get('/role-list',[\App\Http\Controllers\RoleController::class,'lists'])->name('role.list');
    Route::post('/role',[\App\Http\Controllers\RoleController::class,'store'])->name('role.store');
    Route::get('/role/{id}',[\App\Http\Controllers\RoleController::class,'show'])->name('role.show');
    Route::put('/role/{id}',[\App\Http\Controllers\RoleController::class,'update'])->name('role.update');
    Route::delete('/role/{id}',[\App\Http\Controllers\RoleController::class,'destroy'])->name('role.delete');

    Route::get('/payroll',[\App\Http\Controllers\PayrollController::class, 'index'])->name('payroll.index'); 
    Route::get('/show-date',[\App\Http\Controllers\PayrollController::class, 'showDate'])->name('generate.payroll.by.date'); 
    Route::get('/info/{id}',[\App\Http\Controllers\PayrollController::class, 'getSummary']);
    // Route::get('/payroll-commission',[\App\Http\Controllers\PayrollController::class, 'show']);
});

Route::get('/home', [App\Http\Controllers\Dashboard\DashboardController::class, 'index'])->name('home');
