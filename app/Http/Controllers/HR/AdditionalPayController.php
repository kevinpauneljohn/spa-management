<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdditionalPayRequest;
use App\Models\AdditionalPay;
use App\Services\HR\AdditionalPayService;
use Illuminate\Http\Request;

class AdditionalPayController extends Controller
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
     * @param StoreAdditionalPayRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAdditionalPayRequest $request, AdditionalPayService $additionalPayService): \Illuminate\Http\JsonResponse
    {
        if($additionalPayService->save_additional_pay(collect($request->all())->toArray()))
        {
            return response()->json(['success' => true, 'message' => 'Additional Pay Added!']);
        };
        return response()->json(['success' => false, 'message' => 'An error occurred!']);
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
    public function edit($id)
    {
        return AdditionalPay::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreAdditionalPayRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $additionalPay = AdditionalPay::find($id)->fill($request->all());
        if($additionalPay->isDirty())
        {
            $additionalPay->save();
            return response()->json(['success' => true, 'message' => 'Additional pay updated!']);
        }
        return response()->json(['success' => false, 'message' => 'No changes made!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return AdditionalPay::findOrFail($id)->delete() ?
            response()->json(['success' => true, 'message' => 'Additional pay successfully removed!']) :
            response()->json(['success' => false, 'message' => 'An error occurred!']) ;
    }

    public function get_additional_pays($payroll_id, AdditionalPayService $additionalPayService)
    {
        return $additionalPayService->get_additional_pay_datatable($payroll_id);
    }
}
