<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Spa;
use App\Models\Transaction;
use App\Services\PointOfSales\MasseurAvailabilityService;
use App\Services\PointOfSales\RoomAvailabilityService;
use App\Services\PointOfSales\Sales\AmountToBePaid;
use App\Services\PointOfSales\Sales\ClientPayment;
use App\Services\PointOfSales\Sales\IsolateTransaction;
use App\Services\PointOfSales\Sales\SalesService;
use App\View\Components\Pos\Appointments\UpcomingTab\view;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['only_employee_and_owner_of_the_spa'])->only(['addTransactions']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
    public function store(Request $request)
    {
        $sales = Sale::create($request->all());
        if($sales)
        {
            return response()->json(['success' => true, 'message' => 'Successfully created as sales instance', 'sales' => $sales]);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred']);
    }


    /**
     * @param $id
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function show($id, RoomAvailabilityService $service)
    {
        $spa = Spa::findOrFail($id);
        $pageTitle = 'Dashboard';
        $availableRooms = collect($service->availableRooms($id))->count();
        return view('point-of-sale.dashboard',
        compact('pageTitle','spa','availableRooms'));
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);
        return response()->json(['sale' => $sale, 'request' => $request]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        if($sale->transactions->count() > 0)
        {
            return response()->json(['success' => false, 'message' => 'Sales instance cannot be cancelled and deleted!']);
        }
        if($sale->delete())
        {
            return response()->json(['success' => true, 'message' => 'Sales instance successfully deleted']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred']);
    }

    public function addTransactions(Spa $spa, Sale $sale)
    {
        $pageTitle = 'Create Sales Transaction';
        return view('point-of-sale.create-transactions',
        compact('spa','pageTitle','sale'));
    }

    public function salesList(Spa $spa, SalesService $salesService, Request $request)
    {
        if($request->session()->get('salesDateFrom') && $request->session()->get('salesDateTo'))
        {
            $dateFrom = $request->session()->get('salesDateFrom');
            $dateTo = $request->session()->get('salesDateTo');
            $query = $spa->sales()->whereDate('created_at','>=',$dateFrom)
                ->whereDate('created_at','<=', $dateTo)->orWhere(function ($query) use ($spa){
                    $query->where('payment_status','pending')->where('spa_id',$spa->id);
                })->get();
        }
        else{
            $query = $spa->sales;
        }
        return $salesService->salesList($query);
    }

    public function getSalesByDateRange(Request $request, $spaId)
    {
        $date = explode('-',$request->input('date'));
        $request->session()->put('salesDateFrom',Carbon::parse($date[0]));
        $request->session()->put('salesDateTo',Carbon::parse($date[1]));
    }

    /**
     * @param $spaId
     * @param $transactionId
     * @param IsolateTransaction $transaction
     * @return JsonResponse
     */
    public function isolateTransaction($spaId, $transactionId, IsolateTransaction $transaction): \Illuminate\Http\JsonResponse
    {
        if($transaction->isolateTransaction($spaId, $transactionId))
        {
            return response()->json(['success' => true,
                'message' => 'Transaction Successfully Isolated!',
                'sales_id' => Transaction::find($transactionId)->sales_id]);
        }
        return response()->json(['success' => false, 'message' => 'Isolate Transaction is not allowed']);
    }

    public function getAmountToBePaid($salesId, AmountToBePaid $toBePaid): array
    {
        return $toBePaid->invoiceDetails($salesId);
    }

    public function pay(Request $request, $salesId, ClientPayment $payment)
    {
        $paymentType = $request->payment_type;
        $amount = collect($request->all())->has('cash') ? $request->cash : 0;
        $referenceNo = collect($request->all())->has('reference_no') ? $request->reference_no : null;

        if($payment->payment($salesId, $paymentType, $amount, $referenceNo))
        {
            return response()->json(['success' => true, 'message' => 'Payment successful!']);
        }
        return response()->json(['success' => false, 'message' => 'An error occurred!']);
    }

    public function salesActivityLogs($spaId)
    {
        $pageTitle = "Sales Activity Logs";
        $spa = Spa::find($spaId);
        return view('point-of-sale.activity-logs',compact('pageTitle','spa'));
    }

    public function printInvoice(Sale $sale)
    {
        $pageTitle = 'Print Invoice #'.$sale->id;
        return view('point-of-sale.print-invoice',compact('sale','pageTitle'));
    }

    public function displayTherapistsAvailabilityInProgressBar(Spa $spa, MasseurAvailabilityService $service): \Illuminate\Support\Collection
    {
        return $service->displayProgressBar($spa->id);
    }

    public function convertBookingToSales($spaId, $bookingId)
    {

    }

}
