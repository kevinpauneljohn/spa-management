<?php

namespace App\Services;
use App\Models\Client;

class ClientService
{
    public function get_client($data)
    {
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $mobileNo = $data['mobile_number'];

        $client = Client::where(
            'firstname', 'LIKE', '%'.$firstname.'%'
        )->where(
            'lastname', 'LIKE', '%'.$lastname.'%'
        )->where(
            'mobile_number', $mobileNo
        )->first();

        $status = false;
        $data = [];
        if ($client) {
            $status = true;
            $data = $client;    
        }

        $response = [
            'status' => $status,
            'data' => $data,
        ]; 

        return $response;
    }

    public function create($data)
    {
        $client = Client::create([
            'firstname' => $data['firstname'],
            'middlename' => $data['middlename'],
            'lastname' => $data['lastname'],
            'date_of_birth' => $data['date_of_birth'],
            'mobile_number' => $data['mobile_number'],
            'email' => $data['email'],
            'address' => $data['address'],
            'client_type' => $data['client_type'],
        ]);

        $status = false;
        $data = [];
        if ($client) {
            $status = true;
            $data = $client;    
        }

        $response = [
            'status' => $status,
            'data' => $data,
        ]; 

        return $response;
    }

    public function update($id, $data)
    {
        $client = Client::findOrFail($id);

        $client->firstname = $data['firstname'];
        $client->middlename = $data['middlename'];
        $client->lastname = $data['lastname'];
        $client->date_of_birth = $data['date_of_birth'];
        $client->mobile_number = $data['mobile_number'];
        $client->email = $data['email'];
        $client->address = $data['address'];
        $client->client_type = $data['client_type'];

        $status = false;
        $data = [];
        if ($client->save()) {
            $status = true;
            $data = $client;    
        };

        $response = [
            'status' => $status,
            'data' => $data,
        ]; 

        return $response;
    }
}