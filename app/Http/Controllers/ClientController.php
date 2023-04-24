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
}
