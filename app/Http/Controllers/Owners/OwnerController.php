<?php

namespace App\Http\Controllers\Owners;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Owners';
        return view('Owner.index',['title' => $title]);
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
    public function store(Request $request)
    {
        //
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

    public function owner_lists()
    {
        $owners = User::role('super admin')->get();
        return DataTables::of($owners)
            ->editColumn('created_at',function($owners){
                return $owners->created_at->format('M d, Y');
            })
            ->addColumn('fullname',function ($owners){
                return $owners->fullname;
            })
            ->addColumn('qty_of_spa',function ($owners){
                return "";
            })
            ->addColumn('action', function($owners){
                $action = "";
                $action .= '<div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                   Action
                                </button>
                                <div class="dropdown-menu">
                                  <a class="dropdown-item view-reserved-unit" href="#" id="'.$owners->id.'" title="View Details" data-toggle="modal" data-target="#view-sales-details">View</a>
                                  <a class="dropdown-item view-reserved-unit" href="#" id="'.$owners->id.'" title="Edit Details">Edit</a>
                                  <a class="dropdown-item view-requirements" href="#" id="'.$owners->id.'" title="View Requirements" data-toggle="modal" data-target="#view-requirements">Manage Requirements</a>
                                </div>
                              </div>';

                return $action;
            })
            ->rawColumns(['action','status','total_contract_price','requirements','payments'])
            ->make(true);
    }
}
