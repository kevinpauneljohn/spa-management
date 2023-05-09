<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use App\Services\InventoryService;
use App\Services\UserService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role_or_permission:owner|view inventory'])->only(['index','show','lists']);
        $this->middleware(['role_or_permission:owner|add inventory'])->only(['store']);
        $this->middleware(['role_or_permission:owner|edit inventory'])->only(['edit','update']);
        $this->middleware(['role_or_permission:owner|delete inventory'])->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inventories.index');
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
    public function store(InventoryRequest $request, UserService $userService): \Illuminate\Http\JsonResponse
    {
        if(
            Inventory::create(
                collect($request->all())->merge(['owner_id' => $userService->get_staff_owner()->id])->toArray()
            )
        ) return response()->json(['success' => true, 'message' => 'item successfully added!']);

        return response()->json(['success' => false,'message' => 'An error occurred']);

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
     * @return \Illuminate\Http\Response
     */
    public function edit(Inventory $inventory)
    {
        return $inventory;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(InventoryRequest $request, $id)
    {
        if($request->session()->get('password_matched'))
        {
            $inventory = Inventory::findOrFail($id)->fill(collect($request->all())->except(['_token'])->toArray());
            $request->session()->put('password_matched',false);
            if($inventory->isDirty())
            {
                $inventory->save();
                return response()->json(['success' => true, 'message' => 'Item successfully updated!']);
            }
            return response()->json(['success' => false, 'message' => 'No changes made!']);
        }
        return response()->json(['success' => false, 'message' => 'Password mismatched']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Inventory $inventory)
    {
        if($inventory->delete()) return response()->json(['success' => true,'message' => 'Item successfully removed!']);

        return response()->json(['success' => false,'message' => 'An error occurred!']);
    }

    public function lists(InventoryService $inventoryService, UserService $userService)
    {
        $inventories = Inventory::where('owner_id',$userService->get_staff_owner()->id)->get();
        return $inventoryService->inventory_lists($inventories);
    }
}
