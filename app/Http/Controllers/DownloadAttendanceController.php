<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\Spa;
use Illuminate\Http\Request;

class DownloadAttendanceController extends Controller
{   
    public function download($name)
    {
        $spa = Spa::where('name', $name)->first();

        $file = public_path('attendance.html');
        $headers = [
            'Content-Type: text/html',
        ];
        return response()->download($file, 'attendance '.$spa->name.' '.$spa->code.'.html', $headers);


    //     $code = $this->checkLogin();
    //     $collection = collect();
    //     foreach($code as $codes)
    //     {
    //         $count = 0;
    //         $count = $count + 1;
    //         $collection->push([
    //             "name" => $codes['name'],
    //             "code" => $codes['code'],
    //         ]);
 
    //     }
    //    return $collection;

    
    }

    // public function checkLogin()
    // {
    //     if(auth()->check())
    //     {
    //         $user = auth()->user()->id;
    //         $owner = Owner::where('user_id', $user)->get();
    //         $spaCode = Spa::where('owner_id', $owner[0]->id)->get();
    //         $collection = collect();
    //         foreach($spaCode as $data){
    //             $collection->push([
    //                 "name" => $data->name,
    //                 "code" => $data->code,
    //                 "id" => $data->owner_id
    //             ]);
    //         }
    //         return $collection;
    //     }
    // }
}
