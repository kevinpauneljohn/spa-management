<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function getList()
    {
        $client = Client::get();
        
        $data = [];
        foreach ($client as $list) {
            $data [ucfirst($list->firstname).' '.ucfirst($list->lastname).' ['.$list->mobile_number.']'] = $list->id;
        }
        
        return $data;
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['client' => $client]);
    }

    public function filter($id)
    {
        $val_array = explode(' ', $id);

        $client = [];
        foreach ($val_array as $array) {
            $client = Client::where('firstname', 'LIKE', '%'.$array.'%')
            ->orWhere('middlename', 'LIKE', '%'.$array.'%')
            ->orWhere('lastname', 'LIKE', '%'.$array.'%')
            ->get();
        }

        $data = [];
        $status = false;
        if ($client->count() > 0) {
            foreach ($client as $list) {
                $data [ucfirst($list->firstname).' '.ucfirst($list->lastname)] = $list->id;
            }
            $status = true;
        }

        $response = [
            'status'   => $status,
            'data'   => $data,
            'count' => $client->count()
        ]; 

        return $response;
    }
}
