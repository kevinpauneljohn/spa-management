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
    Route::get('/receptionist-lists/{id}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'lists'])->name('receptionist.lists');
    Route::get('/receptionist-service/{id}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'getServices'])->name('receptionist.service');
    Route::get('/receptionist-plus-range',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'plusTime'])->name('receptionist.plus.range');
    Route::get('/receptionist-room-range/{num}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'roomRange'])->name('receptionist.room.range');
    Route::get('/receptionist-therapist/{id}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'getTherapist'])->name('receptionist.therapist');
    Route::post('/create/{id}/{amount}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'store'])->name('receptionist.create');
    Route::put('/update/{id}/{amount}',[\App\Http\Controllers\Receptionists\ReceptionistController::class,'update'])->name('receptionist.update');

    Route::get('/client-list',[\App\Http\Controllers\ClientController::class,'getList'])->name('client.lists');
    Route::get('/client/{id}',[\App\Http\Controllers\ClientController::class,'show'])->name('client.show');
    Route::get('/client-filter/{id}/{spa}',[\App\Http\Controllers\ClientController::class,'filter'])->name('client.filter');

    Route::get('/sales-list/{id}',[\App\Http\Controllers\SaleController::class,'lists'])->name('sale.lists');
    Route::put('/sales-update/{id}',[\App\Http\Controllers\SaleController::class,'updateSales'])->name('sale.update');
    Route::resource('sales',\App\Http\Controllers\SaleController::class);

    Route::get('/transaction/{id}',[\App\Http\Controllers\TransactionController::class,'show'])->name('transaction.show');
    Route::get('/transaction-list/{id}',[\App\Http\Controllers\TransactionController::class,'lists'])->name('transaction.lists');
    Route::get('/transaction-total-sales/{id}',[\App\Http\Controllers\TransactionController::class,'getTotalSales'])->name('transaction.count');
    Route::get('/transaction-masseur-availability/{id}',[\App\Http\Controllers\TransactionController::class,'getTherapistAvailability'])->name('transaction.availability');
    Route::get('/transaction-room-availability/{id}',[\App\Http\Controllers\TransactionController::class,'getRoomAvailability'])->name('transaction.room.availability');
    Route::get('/transaction-data/{id}',[\App\Http\Controllers\TransactionController::class,'getData'])->name('transaction.data');
    Route::get('/transaction-invoice/{id}',[\App\Http\Controllers\TransactionController::class,'getInvoice'])->name('transaction.invoice');

    Route::resource('owners',\App\Http\Controllers\Owners\OwnerController::class);
    Route::get('/owners-list',[\App\Http\Controllers\Owners\OwnerController::class,'owner_lists'])->name('owner.lists');
    Route::post('/owners',[\App\Http\Controllers\Owners\OwnerController::class,'store'])->name('owner.store');
    Route::get('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'show'])->name('owner.show');
    Route::put('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'update'])->name('owner.update');
    Route::delete('/owners/{owner}',[\App\Http\Controllers\Owners\OwnerController::class,'destroy'])->name('owner.delete');
    Route::get('/owner-dashboard',[\App\Http\Controllers\Owners\OwnerController::class,'dashboard'])->name('owner.dashboard');

    Route::get('/spa-list/{id}',[\App\Http\Controllers\SpaController::class,'lists'])->name('spa.lists');
    Route::get('/spa/overview/{id}',[\App\Http\Controllers\SpaController::class,'overview'])->name('spa.overview');

    Route::resource('spa',\App\Http\Controllers\SpaController::class);
    Route::get('/spas',[\App\Http\Controllers\SpaController::class,'getSpaList'])->name('spa.list');

    Route::get('my-spas',[\App\Http\Controllers\SpaController::class,'my_spas'])->name('owner.my.spas');
    Route::get('my-spa-lists',[\App\Http\Controllers\SpaController::class,'get_owner_spas'])->name('owner.list.spas');

    Route::get('my-staff',[\App\Http\Controllers\MyStaffController::class,'my_staffs'])->name('owner.my.staffs');
    Route::get('my-staff-lists',[\App\Http\Controllers\MyStaffController::class,'get_owner_staffs'])->name('owner.list.staffs');
    Route::post('my-staff-create',[\App\Http\Controllers\MyStaffController::class,'store'])->name('owner.staff.create');
    Route::get('my-staff-show/{id}',[\App\Http\Controllers\MyStaffController::class,'show'])->name('owner.staff.show');
    Route::put('my-staff-update/{id}',[\App\Http\Controllers\MyStaffController::class,'update'])->name('owner.staff.update');
    Route::delete('my-staff-delete/{id}',[\App\Http\Controllers\MyStaffController::class,'destroy'])->name('owner.staff.delete');

    Route::get('/therapist-list/{id}',[\App\Http\Controllers\TherapistController::class,'lists'])->name('therapist.lists');
//    Route::get('/therapist/overview/{id}',[\App\Http\Controllers\TherapistController::class,'overview'])->name('therapist.overview');
    Route::get('/therapists-profile/{id}',[\App\Http\Controllers\TherapistController::class,'therapist_profile'])->name('therapists.profile');

    Route::resource('therapists',\App\Http\Controllers\TherapistController::class);

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
    Route::get('/roles',[\App\Http\Controllers\RoleController::class,'getRoleList'])->name('role.lists');

    Route::get('/payroll',[\App\Http\Controllers\PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/show-date',[\App\Http\Controllers\PayrollController::class, 'showDate'])->name('generate.payroll.by.date');
    Route::get('/info/{id}',[\App\Http\Controllers\PayrollController::class, 'getSummary']);
    Route::get('/employee-time',[\App\Http\Controllers\PayrollController::class, 'getEmployeeTime']);


    Route::get('/attendance', [\App\Http\Controllers\EmployeeController::class, 'index'])->name('attendance.index');
    Route::post('/attendanceID/{id}', [\App\Http\Controllers\EmployeeController::class, 'create']);
    Route::get('/time-out/{id}', [\App\Http\Controllers\EmployeeController::class, 'time_out']);
    Route::get('/break-in/{id}', [\App\Http\Controllers\EmployeeController::class, 'break_in']);
    Route::get('/break-out/{id}', [\App\Http\Controllers\EmployeeController::class, 'break_out']);
    Route::get('/show', [\App\Http\Controllers\EmployeeController::class, 'show']);
    // Route::get('/payroll-commission',[\App\Http\Controllers\PayrollController::class, 'show']);

    Route::get('appointment-type',[\App\Http\Controllers\AppointmentController::class,'getAppointmentType'])->name('appointment.type');
    Route::post('appointment-store/{id}',[\App\Http\Controllers\AppointmentController::class,'store'])->name('appointment.store');
    Route::get('appointment-lists/{id}',[\App\Http\Controllers\AppointmentController::class,'lists'])->name('appointment.list');
    Route::get('appointment-show/{id}',[\App\Http\Controllers\AppointmentController::class,'show'])->name('appointment.show');
    Route::get('appointment-count/{id}',[\App\Http\Controllers\AppointmentController::class,'upcoming'])->name('appointment.count');
    Route::put('appointment-update/{id}',[\App\Http\Controllers\AppointmentController::class,'edit'])->name('appointment.update');
    Route::post('appointment-sales',[\App\Http\Controllers\AppointmentController::class,'sales'])->name('appointment.sales');
    Route::post('/appointment-create-sales/{id}/{amount}',[\App\Http\Controllers\AppointmentController::class,'storeSales'])->name('appointment.create.sales');
    Route::delete('appointment-delete/{id}',[\App\Http\Controllers\AppointmentController::class,'destroy'])->name('appointment.delete');

    Route::get('/appointment-upcoming/{id}',[\App\Http\Controllers\AppointmentController::class,'getUpcomingGuests'])->name('appointment.upcoming.guest');
    Route::get('/appointment-response/{id}',[\App\Http\Controllers\AppointmentController::class,'getResponses'])->name('appointment.responses');

    Route::resource('inventories',\App\Http\Controllers\InventoryController::class);
    Route::get('/inventory-lists',[\App\Http\Controllers\InventoryController::class,'lists'])->name('inventory.lists');
    Route::put('/update-inventory-quantity/{inventory}',[\App\Http\Controllers\InventoryController::class,'increase_quantity'])->name('update.inventory.quantity');

    Route::resource('inventory-categories',\App\Http\Controllers\Inventories\InventoryCategoryController::class);
    Route::get('/inventory-category-lists',[\App\Http\Controllers\Inventories\InventoryCategoryController::class,'lists'])->name('inventory.category.lists');


    Route::post('/check-user-password',[\App\Http\Controllers\UserController::class,'check_user_logged_in_password'])->name('check.user.password');
});

Route::get('/home', [App\Http\Controllers\Dashboard\DashboardController::class, 'check_user_logged_in_password'])->name('home');
