<?php

namespace App\Services;
use App\Models\Client;
use App\Models\Owner;
use App\Models\Spa;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ClientService
{
    public function get_client($data)
    {
        $spa = Spa::findOrFail($data['spa_id']);
        $owner = Owner::find($spa->owner_id);

        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $mobileNo = $data['mobile_number'];

        $client = Client::where(
            'firstname', 'LIKE', '%'.$firstname.'%'
        )->where(
            'lastname', 'LIKE', '%'.$lastname.'%'
        )->where(
            'mobile_number', 'LIKE', '%'.$mobileNo.'%'
        )->first();

        $status = false;
        $owner_client = false;
        $data = [];
        if ($client) {
            $status = true;
            $data = $client;
            $check_owner = $owner->clients();
            if ($check_owner) {
                $owner_client = true;
            }
        }

        $response = [
            'status' => $status,
            'data' => $data,
            'owner_client' => $owner_client
        ];

        return $response;
    }

    public function create($data)
    {
        $status = false;
        $value = [];
        $spa = Spa::findOrFail($data['spa_id']);
        $owner = Owner::findOrFail($spa->owner_id);
        DB::beginTransaction();
        try {
            $client = Client::create([
                'firstname' => strtolower($data['firstname']),
                'middlename' => strtolower($data['middlename']),
                'lastname' => strtolower($data['lastname']),
                'date_of_birth' => $data['date_of_birth'],
                'mobile_number' => $data['mobile_number'],
                'email' => strtolower($data['email']),
                'address' => strtolower($data['address']),
                'client_type' => $data['client_type'],
            ]);

            if ($client) {
                $owner->clients()->attach($client->id);
                $status = true;
                $value = $client;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $code = 500;
            $status = false;
            $message = $e->getMessage();
        }

        $response = [
            'status' => $status,
            'data' => $value
        ];

        return $response;
    }

    public function update($id, $data)
    {
        $spa = Spa::findOrFail($data['spa_id']);
        $owner = Owner::findOrFail($spa->owner_id);
        $client = Client::findOrFail($id);

        $client->firstname = strtolower($data['firstname']);
        $client->middlename = strtolower($data['middlename']);
        $client->lastname = strtolower($data['lastname']);
        $client->date_of_birth = $data['date_of_birth'];
        $client->mobile_number = $data['mobile_number'];
        $client->email = strtolower($data['email']);
        $client->address = strtolower($data['address']);
        $client->client_type = $data['client_type'];

        $status = false;
        $data = [];
        if ($client->save()) {
            $status = true;
            $data = $client;
        };

        $response = [
            'status' => $status,
            'data' => $data
        ];

        return $response;
    }

    public function updateClient($request, $id): \Illuminate\Http\JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->firstname = $request->firstname;
        $client->middlename = $request->middlename;
        $client->lastname = $request->lastname;
        $client->date_of_birth = $request->date_of_birth;
        $client->mobile_number = $request->mobile_number;
        $client->email = $request->email;
        $client->address = $request->address;
        if($client->isDirty())
        {
            $client->save();
            return response()->json(['success' => true, 'message' => 'Client successfully updated']);
        }
        return response()->json(['success' => false, 'message' => 'No changes made']);
    }

    public function client($client_id)
    {
        return Client::findOrFail($client_id);
    }

    public function clientTransactions($client_id)
    {
        $transactions = $this->client($client_id)->transaction;
        return DataTables::of($transactions)
            ->editColumn('start_date', function($transaction){
                return '<span class="text-primary">'.$transaction->start_date.'</span>';
            })
            ->editColumn('end_date', function($transaction){
                return '<span class="text-primary">'.$transaction->end_date.'</span>';
            })
            ->editColumn('room_id', function($transaction){
                return '<span class="text-info text-bold">#'.$transaction->room_id.'</span>';
            })
            ->editColumn('spa_id', function($transaction){
                return $transaction->spa->name;
            })
            ->editColumn('duration', function($transaction){
                return '<span class="text-danger text-bold">'.$transaction->service->duration.' mins</span>';
            })
            ->addColumn('payable_amount', function($transaction){
                return '<span class="text-danger text-bold">'.number_format($transaction->total_amount,2).'</span>';
            })
            ->addColumn('invoice_number', function($transaction){
                return '<a href="/point-of-sale/add-transaction/'.$transaction->spa_id.'/'.$transaction->sales_id.'" target="_blank">'.$transaction->sale->invoice_number.'</a>';
            })
            ->addColumn('therapists', function($transaction){
                $therapist = '<span class="badge badge-info m-1">'.$transaction->therapist->full_name.'</span>';
                if($transaction->therapist2 !== null) $therapist .= '<span class="badge badge-success m-1">'.$transaction->therapist2->full_name.'</span>';

                return $therapist;

            })
            ->rawColumns(['invoice_number','duration','start_date','end_date','room_id','spa_id','therapists','payable_amount'])
            ->with([
                'total_transactions' => Transaction::where('client_id',$client_id)->count()
            ])
            ->make(true);
    }
}
