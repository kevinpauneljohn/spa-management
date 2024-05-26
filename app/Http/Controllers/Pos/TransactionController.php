<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalesTransactionRequest;
use App\Models\Discount;
use App\Models\Spa;
use App\Models\Transaction;
use App\Services\PointOfSales\MasseurAvailabilityService;
use App\Services\PointOfSales\RoomAvailabilityService;
use App\Services\PointOfSales\VoidTransaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;


class TransactionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $pageTitle = 'Transaction Logs';
        $spa = Spa::find('97d9f261-6d8a-4954-94ff-dad7a6b94b57');
        return view('point-of-sale.transaction-logs',compact('pageTitle','spa'));
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
    public function store(SalesTransactionRequest $request, \App\Services\PointOfSales\TransactionService $transactionService): JsonResponse
    {
        if($transactionService->checkIfClientExistsFromTransactions($request->sales_id, $request->client_id) && !collect($request)->has('confirm'))
        {
            return response()->json(['success' => false, 'message' => 'client already exist']);
        }
            $transaction = $transactionService->saveClient($request);
            return response()->json([
                'success' => true,
                'message' => 'Client successfully added!',
                'transaction' => $transaction]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @param \App\Services\PointOfSales\TransactionService $service
     * @return mixed
     */
    public function show($id, \App\Services\PointOfSales\TransactionService $service)
    {
        return $service->transaction($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param $spaId
     * @param $saleId
     * @param TransactionService $transactionService
     * @return mixed
     */
    public function transactionClientLists($spaId, $saleId, TransactionService $transactionService)
    {
        return $transactionService->clientTransactionLists($spaId, $saleId);
    }

    /**
     * retrieve the available therapists
     * @param $spaId
     * @param MasseurAvailabilityService $masseurAvailabilityService
     * @return mixed
     */
    public function masseurAvailability($spaId, MasseurAvailabilityService $masseurAvailabilityService)
    {
        return $masseurAvailabilityService->masseurs($spaId);
    }

    /**
     * @param $spaId
     * @param RoomAvailabilityService $roomAvailabilityService
     * @return Collection
     */
    public function roomAvailability($spaId, RoomAvailabilityService $roomAvailabilityService): Collection
    {
        return $roomAvailabilityService->availableRooms($spaId);
    }

    public function roomAvailabilityDashboardChecker($spaId, RoomAvailabilityService $roomAvailabilityService): JsonResponse
    {
        return response()->json([
            'available' => $roomAvailabilityService->availableRooms($spaId),
            'complete' => collect($roomAvailabilityService->availableRooms($spaId))->count() == Spa::find($spaId)->number_of_rooms,
            'taken' => $roomAvailabilityService->excludedRooms($spaId)
        ]);
    }

    /**
     * @param Request $request
     * @param $transactionId
     * @param VoidTransaction $voidTransaction
     * @return JsonResponse
     */
    public function voidTransaction(Request $request, $transactionId, VoidTransaction $voidTransaction): \Illuminate\Http\JsonResponse
    {
        if($request->reason == "")
        {
            return response()->json(['success' => false, 'message' => 'Reason is required'],400);
        }

        if($voidTransaction->voidTransaction($transactionId, $request->reason))
        {
            return response()->json(['success' => true, 'message' => 'Transaction Voided']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred'],500);
    }


    public function extendTime(Request $request, $transaction, \App\Services\PointOfSales\TransactionService $service): JsonResponse
    {
        if($service->extendTime($transaction, $request->time))
        {
            return response()->json(['success' => true, 'message' => 'Time extended!']);
        }
        return response()->json(['success' => false, 'message' => 'No changes made!']);
    }

    public function underTime($transactionId, \App\Services\PointOfSales\TransactionService $transactionService): JsonResponse
    {
        return $transactionService->underTime($transactionId) ?
            \response()->json(['success' => true, 'message' => 'Transaction completed']):
            \response()->json(['success' => false, 'message' => 'An error occurred']);
    }

    public function saveCouponToTransaction($transaction, Request $request, \App\Services\PointOfSales\TransactionService $transactionService): JsonResponse
    {
        $request->validate([
            'discount_code' => ['required']
        ]);

        return $transactionService->claimCoupon($transaction, $request->input('discount_id')) ?
            \response()->json(['success' => true, 'message' => 'Discount applied!']) :
            \response()->json(['success' => false, 'message' => 'An error occurred']);
    }
    public function removeCouponFromTransaction($transaction, \App\Services\PointOfSales\TransactionService $transactionService): JsonResponse
    {
        return $transactionService->voidTransactionCoupon($transaction) ?
            \response()->json(['success' => true, 'message' => 'Discount removed!']) :
            \response()->json(['success' => false, 'message' => 'An error occurred']);
    }
}
