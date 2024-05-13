<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Services\ExpenseService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view expenses')->only(['index','show']);
        $this->middleware('permission:add expenses')->only(['store']);
        $this->middleware('permission:edit expenses')->only(['edit','update']);
        $this->middleware('permission:delete expenses')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(UserService $userService, ExpenseService $expenseService)
    {
        $pageTitle = 'Expenses';
        $owner = $userService->get_staff_owner(); //you may now call any related models
        $spaId = $owner->spas->first()->id;
        return view('expenses.index',compact('owner','pageTitle','spaId'));
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
    public function store(ExpenseRequest $request)
    {
        Expense::create($request->all());
        return response()->json(['success' => true, 'message' => 'Expense successfully added!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return Expense
     */
    public function edit(Expense $expense): Expense
    {
        return $expense;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->fill(collect($request->all())->except(['_token'])->toArray());

        if($expense->isDirty())
        {
            $expense->save();
            return response()->json(['success' => true,'message' => 'Expense successfully updated!']);
        }
        return response()->json(['success' => false,'message' => 'No changes made!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Expense $expense): \Illuminate\Http\JsonResponse
    {
        if($expense->delete())
        {
            return response()->json([
                'success' => true,
                'message' => 'Expense successfully removed!'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'An error occurred!'
        ]);
    }

    public function displayExpensesByDateSelected(Request $request)
    {
        $date = explode('-',$request->input('date'));
        $request->session()->put('dateFrom',Carbon::parse($date[0]));
        $request->session()->put('dateTo',Carbon::parse($date[1]));
    }
}
