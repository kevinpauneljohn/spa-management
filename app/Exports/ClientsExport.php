<?php

namespace App\Exports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ClientsExport implements FromView
{
    public function view(): \Illuminate\Contracts\View\View
    {
        return view('clients.export', [
            'clients' => collect(Client::where('mobile_number','!=',null)->get())->sortBy('firstname')
        ]);
    }
}
