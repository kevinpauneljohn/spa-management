<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientUpdateRequest;
use App\Models\Client;
use App\Services\ClientService;
use App\Services\UserService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public $user;

    public function __construct(UserService $userService)
    {
        $this->user = $userService;
        $this->middleware(['permission:view client'])->only(['index','clientTransactionLists']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     */
    public function index(Request $request)
    {
        $clients = $this->user->get_staff_owner()->clients()
            ->where('firstname','LIKE','%'.$request->input('search_client').'%')
            ->orWhere('middlename','LIKE','%'.$request->input('search_client').'%')
            ->orWhere('lastname','LIKE','%'.$request->input('search_client').'%')
            ->paginate(10);
        return view('clients.index',compact('clients'));
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
    public function show($id, UserService $userService)
    {
        $client =  $userService->get_staff_owner()->clients()->where('id',$id)->first();
        return view('clients.show',compact('client'));
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
    public function update(ClientUpdateRequest $request, $id, ClientService $clientService): \Illuminate\Http\JsonResponse
    {
        return $clientService->updateClient($request, $id);
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

    public function clientTransactionLists($client, ClientService $clientService)
    {
        return $clientService->clientTransactions($client);
    }
}
