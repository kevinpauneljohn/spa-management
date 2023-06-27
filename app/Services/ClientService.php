<?php

namespace App\Services;
use App\Models\Client;
use App\Models\Owner;
use App\Models\Spa;
use Illuminate\Support\Facades\DB;

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
}