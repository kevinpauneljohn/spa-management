<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Services\HR\DepartmentService;
use App\Services\UserService;
use App\View\Components\Pos\Appointments\UpcomingTab\view;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    public $userService;
    public function __construct()
    {
        $this->middleware(['permission:view department'])->only(['index','lists']);
        $this->middleware(['permission:add department'])->only(['store']);
        $this->middleware(['permission:edit department'])->only(['edit','update']);
        $this->middleware(['permission:delete department'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|Response
     */
    public function index()
    {
        return view('hr.departments.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
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
    public function store(Request $request, DepartmentService $departmentService, UserService $userService): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required',
        ]);
        if($departmentService->saveDepartment(
            $userService->get_staff_owner()->id,
            $request->name,
            auth()->user()->id
        ))
        {
            return \response()->json([
                'success' => true,
                'message' => 'Department created successfully'
            ]);
        }
        return \response()->json([
            'success' => false,
            'message' => 'An error occurred while creating the department'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return int
     */
    public function edit($id)
    {
        return Department::findOrfail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @param DepartmentService $departmentService
     * @param UserService $userService
     * @return JsonResponse
     */
    public function update(Request $request, $id, DepartmentService $departmentService, UserService $userService): JsonResponse
    {
        $response = $departmentService->updateDepartment($id,
            $userService->get_staff_owner()->id,
            $request->name,
            auth()->user()->id
        );
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $department = Department::findOrFail($id);
        if($department->delete())
        {
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the department'
        ]);
    }

    /**
     * @param DepartmentService $departmentService
     * @return mixed
     */
    public function lists(DepartmentService $departmentService)
    {
        return $departmentService->departments();
    }
}
