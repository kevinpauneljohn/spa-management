<?php

namespace App\Services;

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
            ->addColumn('action', function($inventory){
                $action = "";
                if(auth()->user()->can('view inventory'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-success mr-1" title="View"><i class="fas fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit inventory'))
                {
                    $action .= '<button class="btn btn-sm btn-outline-primary edit-inventory-btn mr-1" id="'.$inventory->id.'"><i class="fa fa-edit"></i></button>';
                }
                if(auth()->user()->can('delete inventory'))
                {
                    $action .= '<button class="btn btn-sm btn-outline-danger delete-inventory-btn mr-1" id="'.$inventory->id.'"><i class="fa fa-trash"></i></button>';
                }
                return $action;
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
                    $action .= '<a href="#" class="btn btn-sm btn-outline-success mr-1" title="View"><i class="fas fa-eye"></i></a>';
                }
                if(auth()->user()->can('edit category'))
                {
                    $action .= '<button class="btn btn-sm btn-outline-primary edit-category-btn mr-1" id="'.$category->id.'"><i class="fa fa-edit"></i></button>';
                }
                if(auth()->user()->can('delete category'))
                {
                    $action .= '<button class="btn btn-sm btn-outline-danger delete-category-btn mr-1" id="'.$category->id.'"><i class="fa fa-trash"></i></button>';
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

}
