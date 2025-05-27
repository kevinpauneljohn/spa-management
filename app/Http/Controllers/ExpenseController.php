<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use App\Models\Spa;
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
        $spa = auth()->user()->spa;

        if (auth()->user()->hasRole('admin')) {
            return view('expenses.spa_expenses',compact('pageTitle'));
        }
        return view('expenses.index',compact('pageTitle','spa'));
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
        Expense::create(collect($request->all())->merge(['user_id' => auth()->user()->id])->toArray());
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
        $expense->fill(collect($request->all())->except(['_token'])->merge(['user_id' => auth()->user()->id])->toArray());

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
