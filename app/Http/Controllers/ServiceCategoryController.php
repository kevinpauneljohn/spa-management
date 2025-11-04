<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceCategoryRequest;
use App\Http\Requests\UpdateServiceCategoryRequest;
use App\Models\ServiceCategory;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreServiceCategoryRequest $request, CategoryService $categoryService): \Illuminate\Http\JsonResponse
    {
        return $categoryService->saveCategory($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceCategory $serviceCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceCategory $serviceCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateServiceCategoryRequest $request, $service_category, CategoryService $categoryService): \Illuminate\Http\JsonResponse
    {
        return $categoryService->updateCategory($service_category, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceCategory  $serviceCategory
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ServiceCategory $serviceCategory)
    {
        return $serviceCategory->delete() ?
            response()->json(['success' => true, 'message' => 'Service Category has been deleted.']) :
            response()->json(['success' => false, 'message' => 'Service Category could not be deleted.']);
    }

    public function getServiceCategories($spa_id, CategoryService  $categoryService)
    {
        return $categoryService->getCategories($spa_id);
    }
}
