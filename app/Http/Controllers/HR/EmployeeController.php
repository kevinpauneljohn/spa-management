<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Spa;
use App\Models\User;
use App\Services\HR\EmployeeService;
use App\Services\UserService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(UserService $userService){

        $this->middleware(['permission:view employee'])->only(['index','displayEmployees']);
        $this->middleware(['permission:add employee'])->only(['store']);
        $this->middleware(['permission:edit employee'])->only(['edit','update']);
        $this->middleware(['permission:delete employee'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(UserService $userService)
    {
        $excludedRoles = ['super admin', 'owner', 'admin'];
        return view('hr.employees.index',[
            'roles' => Role::whereNotIn('name', $excludedRoles)->get(),
            'spas' => Spa::where('owner_id',$userService->get_staff_owner()->id)->get()
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
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
