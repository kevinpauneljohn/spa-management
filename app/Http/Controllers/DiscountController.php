<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Services\DiscountService;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DiscountController extends Controller
{
    public $discountService;
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
        $this->discountService->checkPermissions();
        $this->middleware(['permission:access discounts'])->only(['index','discountTable']);
    }
    /**
     * @return Application|Factory|View
     */
    public function index(UserService $userService)
    {
        $clients = $userService->get_staff_owner()->clients;
        return view('Discounts.index',compact('clients'));
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


    public function store(StoreDiscountRequest $request, DiscountService $discountService): \Illuminate\Http\JsonResponse
    {
        return $discountService->saveDiscount($request) ?
            response()->json(['success' => true, 'message' => 'Voucher/Coupon successfully created!']) :
            response()->json(['success' => false, 'message' => 'An error occurred']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function show(Discount $discount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDiscountRequest  $request
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        //
    }

    public function deleteDiscount(Discount $discount): \Illuminate\Http\JsonResponse
    {
        return $discount->delete() ?
            response()->json(['success' => true, 'message' => 'Coupon/Voucher successfully removed!']) :
            response()->json(['success' => false, 'message' => 'An error occurred']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Discount  $discount
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Discount $discount, DiscountService $discountService)
    {
        return $discountService->removeVoucher($discount->id) ?
            response()->json(['success' => true, 'message' => 'Voucher successfully removed']) :
            response()->json(['success' => false, 'message' => 'An error occurred']) ;
    }

    public function discountTable()
    {
        return $this->discountService->discountTable(Discount::all());
    }

    public function generateCode($id, DiscountService $discountService): \Illuminate\Http\JsonResponse
    {
        $discount = Discount::findOrFail($id);
        return response()->json(['discount' => $discount, 'code' => $discountService->generateQrCode($discount->code)]);
    }

    public function getDiscount($code, DiscountService $discountService)
    {
        return $discountService->getDiscountByCode($code);
    }
}
