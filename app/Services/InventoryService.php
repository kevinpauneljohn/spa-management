<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use Yajra\DataTables\DataTables;

class InventoryService
{
    public function inventory_lists($inventories)
    {
        return DataTables::of($inventories)
            ->editColumn('spa_id',function($inventory){
                return '<a href="'.route('spa.show',['spa' => $inventory->spa->id]).'">'.$inventory->spa->name.'</a>';
            })
            ->editColumn('name',function($inventory){
                return ucwords($inventory->name);
            })
            ->editColumn('category',function($inventory){
                return InventoryCategory::find($inventory->category)->name;
            })
            ->editColumn('updated_at',function($inventory){
                return $inventory->updated_at->format('Y-m-d h:i:s a');
            })
            ->editColumn('user_id',function($inventory){
                return is_null($inventory->user_id) ? '' : ucwords($inventory->user->full_name);
            })
            ->addColumn('action', function($inventory){
                $action = "";
                if(auth()->user()->can('manage inventory'))
                {
                    $action .= '<button class="btn btn-sm bg-gradient-success update-inventory mr-1 mb-1" title="Manage inventory" id="'.$inventory->id.'"><i class="fas fa-fw fa-shopping-cart"></i></button>';
                }
                if(auth()->user()->can('edit inventory'))
                {
                    $action .= '<button class="btn btn-sm bg-gradient-info edit-inventory-btn mr-1 mb-1" id="'.$inventory->id.'"><i class="fa fa-edit"></i></button>';
                }
                if(auth()->user()->can('delete inventory'))
                {
                    $action .= '<button class="btn btn-sm bg-gradient-orange delete-inventory-btn mr-1 mb-1" id="'.$inventory->id.'"><i class="text-white fa fa-trash"></i></button>';
                }
                return $action;
            })
            ->setRowClass(function($inventory){
                return $inventory->quantity <= $inventory->restock_limit ? 're-stock-required' : '';
            })
            ->rawColumns(['action','spa_id'])
            ->make(true);
    }

    public function category_lists($categories)
    {
        return DataTables::of($categories)
            ->addColumn('action', function($category){
                $action = "";
                if(auth()->user()->can('view category'))
                {
                    $action .= '<a href="#" class="btn btn-sm bg-gradient-success mr-1 mb-1" title="View"><i class="fas fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit category'))
                {
                    $action .= '<button class="btn btn-sm bg-gradient-info edit-category-btn mr-1 mb-1" id="'.$category->id.'"><i class="fa fa-edit"></i></button>';
                }
                if(auth()->user()->can('delete category'))
                {
                    $action .= '<button class="btn btn-sm bg-gradient-orange text-white delete-category-btn mr-1 mb-1" id="'.$category->id.'"><i class="fa fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function categories(UserService $userService)
    {
        return InventoryCategory::where('owner_id', $userService->get_staff_owner()->id)->get();
    }

    public function updateQuantity($inventory_id, $action, $quantity): bool
    {
        $inventory = Inventory::findOrFail($inventory_id);
        $inventory->quantity = $action === 'increase' ?
            $inventory->quantity + $quantity :
            $inventory->quantity - $quantity;
        $inventory->user_id = auth()->user()->id;
        return (bool)$inventory->save();
    }

}
