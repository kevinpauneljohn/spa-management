<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpaRequest;
use App\Services\ExpenseService;
use App\Services\OwnerServices;
use App\Services\SpaService;
use App\Models\Spa;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Http\Request;

class SpaController extends Controller
{
    public function __construct()
    {
        $this->middleware('check.if.user.is.owner')->only(['my_spas','get_owner_spas']);
        $this->middleware(['check.if.user.is.owner','check.if.spa.belongs.to.owner'])->only(['show']);
    }
    public function lists(SpaService $spaService, $id)
    {
        $owner = Owner::where('user_id', $id)->first();
        $spa = $owner->spas;
        return $spaService->spas($spa);
    }

    public function store(SpaRequest $request)
    {
        Spa::create(collect($request->all())->merge(['owner_id' => auth()->user()->owner->id])->toArray());
        return response('Spa Created Successfully',200);
    }

    public function show(Spa $spa, OwnerServices $ownerServices)
    {
        $owner = $ownerServices->getOwnerBySpaID($spa->id);
        $range = range(5, 300, 5);
        return view('Spa.profile',  compact('spa','owner','range'));
    }

    public function edit(Spa $spa)
    {
        return $spa;
    }

    public function update(SpaRequest $request,Spa $spa)
    {
        $spa->fill($request->all());
        if($spa->isDirty()){
            $spa->save();
            return response()->json(['status' => true, 'message' => 'Spa information successfully updated.']);
        } else {
            return response()->json(['status' => false, 'message' => 'No changes made.']);
        }
    }

    public function destroy($id)
    {
        $spa = Spa::findOrFail($id);

        $status = false;
        $message = 'Spa information could not be deleted.';
        if ($spa->delete()) {
            $status = true;
            $message = 'Spa information successfully deleted.';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }

    public function overview($id)
    {
        $owners = User::role(['owner'])->where('id', $id)->first();
        $roles = $owners->getRoleNames()->first();

        return view('Spa.overview',compact('owners', 'roles'));
    }

    public function get_owner_spas(SpaService $spaService)
    {
        return $spaService->spas(auth()->user()->owner->spas);
    }
    public function my_spas()
    {
        return view('Owner.spa.index');
    }

    public function getSpaList(SpaService $spaService)
    {
        return $spaService->get_spa_lists(auth()->user()->owner->id);
    }

    public function spaExpenses(Spa $spa, ExpenseService $expenseService, Request $request)
    {
        $query = $spa->expenses;
        if($request->session()->get('dateFrom') && $request->session()->get('dateTo'))
        {
            $query = $spa->displayExpensesFromDateRange($request->session()->get('dateFrom'), $request->session()->get('dateTo'))->get();

        }

        return $expenseService->expenses(collect($query)->sortByDesc('created_at'));
    }


    /**
     * display all expenses of a specific spa
     * @param $spaId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function displaySpaExpenses(Spa $spa)
    {
        $pageTitle = 'Expenses';
        $spaId = $spa->id;
        return view('expenses.index',compact('pageTitle','spaId','spa'));
    }

    /**
     * display all the appointments in the calendar
     * @param Spa $spa
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function appointmentCalendar(Spa $spa)
    {
        $pageTitle = 'Calendar';
        return view('Spa.calendar',
        compact('pageTitle','spa'));
    }

    /**
     * display the spa inventory management in the view
     * @param Spa $spa
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function spaInventory(Spa $spa)
    {
        return view('Spa.inventory',compact('spa'));
    }

    public function retrieveService(Spa $spa, $serviceName)
    {
        return $spa->getServiceByName($serviceName);
    }

    public function retrieveServiceById(Spa $spa, $serviceId)
    {
        return $spa->getServiceById($serviceId);
    }

}
