<?php

namespace App\Services;

use App\Models\ServiceCategory;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryService
{
    public function saveCategory(array $data): \Illuminate\Http\JsonResponse
    {
        if(ServiceCategory::create($data))
        {
            return response()->json(['success'=> true,'message'=>'Category added successfully']);
        }
        return response()->json(['success' => false,'message'=>'Something went wrong']);
    }

    public function updateCategory($service_Category_id, array $data): \Illuminate\Http\JsonResponse
    {
        $serviceCategory = ServiceCategory::findOrFail($service_Category_id)->fill($data);
        if($serviceCategory->isDirty())
        {
            $serviceCategory->save();
            return response()->json(['success'=> true,'message'=>'Category updated successfully']);
        }
        return response()->json(['success' => false,'message'=>'No changes have been made']);
    }
    public function getCategories($spa_id)
    {
        $serviceCategories = ServiceCategory::where('spa_id', $spa_id)->get();
        return DataTables::of($serviceCategories)
            ->addColumn('action', function ($serviceCategory) {
                $action = '';
                if(auth()->user()->can('edit service'))
                {
                    $action .= '<button type="button" id="' . $serviceCategory->id . '" class="edit-service-category-btn btn bg-gradient-info btn-sm m-1"><i class="fa fa-edit"></i></button>';
                }
                if(auth()->user()->can('delete service'))
                {
                    $action .= '<button type="button" id="' . $serviceCategory->id . '" class="delete-service-category-btn btn bg-gradient-orange btn-sm text-white"><i class="fa fa-trash"></i></button>';
                }
                return $action;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
