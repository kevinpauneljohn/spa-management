<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\StoreEmployeeToBiometricsRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Spa;
use App\Models\User;
use App\Services\HR\BiometricsService;
use App\Services\HR\EmployeeService;
use App\Services\HR\ScheduleSettingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Rats\Zkteco\Lib\ZKTeco;

class EmployeeController extends Controller
{
    public function __construct(UserService $userService){

        $this->middleware(['permission:view employee'])->only(['index','displayEmployees']);
        $this->middleware(['permission:add employee'])->only(['store']);
        $this->middleware(['permission:edit employee'])->only(['edit','update']);
        $this->middleware(['permission:delete employee'])->only(['destroy']);
        $this->middleware(['allowedUsersOnly'])->only(['show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(UserService $userService, BiometricsService $biometricsService)
    {
        $excludedRoles = ['super admin', 'owner', 'admin'];
        return view('hr.employees.index',[
            'roles' => Role::whereNotIn('name', $excludedRoles)->get(),
            'spas' => Spa::where('owner_id',$userService->get_staff_owner()->id)->get(),
            'biometricsUsers' => $biometricsService->getBioMetricsUsers()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEmployeeRequest $request, UserService $userService, EmployeeService $employeeService)
    {
        return response()->json($employeeService->createEmployee(
            $request->all(),
            $request->role,
            $userService->get_staff_owner()->id
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show($id, UserService $userService)
    {
        return view('hr.employees.show',[
            'employee' => Employee::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function edit($id)
    {
        return [
            'employee' => $employee = Employee::findOrFail($id),
            'user' => $employee->user,
            'role' => $employee->user->getRoleNames(),
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateEmployeeRequest $request, $id, EmployeeService $employeeService)
    {
        return response()->json($employeeService->updateUserByEmployeeId(
            $id,
            $request->all(),
            $request->role
        ));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        User::findOrFail($employee->user->id)->delete();
        $employee->delete();
        return response()->json(['success'=> true, 'message' => 'Employee deleted successfully.']);
    }

    public function displayEmployees(EmployeeService $employeeService, UserService $userService)
    {
        return $employeeService->getEmployees($userService->get_staff_owner()->id);
    }

    public function testBiometricsConnection(EmployeeService $employeeService): \Illuminate\Http\JsonResponse
    {
        return response()->json($employeeService->isBiometricsConnected('192.168.254.10'));
    }

    public function addEmployeeToBiometrics(StoreEmployeeToBiometricsRequest $request,$id, EmployeeService $employeeService): \Illuminate\Http\JsonResponse
    {
        return response()->json($employeeService->saveEmployeeToBiometricsTable(
            $request->biometric_users,
            $id,
        ));
    }
}
