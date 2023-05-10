<?php

namespace App\Http\Controllers\Inventories;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryCategoryRequest;
use App\Models\InventoryCategory;
use App\Services\InventoryService;
use App\Services\UserService;
use Illuminate\Http\Request;

class InventoryCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role_or_permission:owner|view category'])->only(['index','show','lists']);
        $this->middleware(['role_or_permission:owner|add category'])->only(['store']);
        $this->middleware(['role_or_permission:owner|edit category'])->only(['edit','update']);
        $this->middleware(['role_or_permission:owner|delete category'])->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inventories.categories.index');
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
    public function store(InventoryCategoryRequest $request, UserService $userService): \Illuminate\Http\JsonResponse
    {
        InventoryCategory::create(collect($request->all())->merge(['owner_id' => $userService->get_staff_owner()->id])->toArray());
        return response()->json(['success' => true,'message' => 'Category successfully added!']);
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
     * @return InventoryCategory
     */
    public function edit(InventoryCategory $inventoryCategory): InventoryCategory
    {
        return $inventoryCategory;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InventoryCategoryRequest $request, InventoryCategory $inventoryCategory): \Illuminate\Http\JsonResponse
    {
        $inventoryCategory->fill($request->all());

        if($inventoryCategory->isDirty())
        {
            $inventoryCategory->save();
            return response()->json(['success' => true, 'message' => 'Category Successfully Updated']);
        }
        return response()->json(['success' => false, 'message' => 'No changes made']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(InventoryCategory $inventoryCategory): \Illuminate\Http\JsonResponse
    {
        if($inventoryCategory->delete()) return response()->json(['success' => true, 'message' => 'Category successfully removed!']);

        return response()->json(['success' => false, 'message' => 'An error occurred!']);
    }

    public function lists(InventoryService $inventoryService, UserService $userService)
    {
        $categories = InventoryCategory::where('owner_id',$userService->get_staff_owner()->id)->get();
        return $inventoryService->category_lists($categories);
    }
}
