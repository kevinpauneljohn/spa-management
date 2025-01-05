<?php

namespace App\Services\HR;

use App\Models\Biometric;
use Carbon\Carbon;
use Rats\Zkteco\Lib\ZKTeco;
use Yajra\DataTables\Facades\DataTables;

class BiometricsService
{
    public function getBioMetricsUsers(): ?array
    {
        $zk = new ZKTeco('192.168.254.10');
        return $zk->connect() ? $zk->getUser() : null;
    }

    public function getBioMetricsAttendances($ipAddress): ?array
    {
        $zk = new ZKTeco($ipAddress);
        return $zk->connect() ? $zk->getAttendance() : null;
    }

    public function getAttendanceInTableFormat($ipAddress)
    {
        return DataTables::of($this->getBioMetricsAttendances($ipAddress))
            ->addColumn('name', function($biometric){
                $biometric = Biometric::where('userid',$biometric['id']);
                return $biometric->count() > 0 ? '<a href="'.route('employees.show',['employee' => $biometric->first()->employee->id]).'">'.ucwords($biometric->first()->employee->user->fullname).'</a>' : '';
            })
            ->editColumn('timestamp', function($biometric){
                return Carbon::parse($biometric['timestamp'])->format('M-d-Y h:i:s a');
            })
            ->editColumn('type', function($biometric){
                if($biometric['type'] == 10)
                {
                    return '<span class="badge badge-success">Check In</span>';
                }
                elseif($biometric['type'] == 11)
                {
                    return '<span class="badge badge-info">Check Out</span>';
                }
                elseif($biometric['type'] == 4)
                {
                    return '<span class="badge bg-purple">Break In</span>';
                }
                else{
                    return '<span class="badge bg-pink">Break In</span>';
                }
            })
            ->addColumn('action',function($biometric){
                return '';
            })
            ->rawColumns(['name','type','action'])
            ->make(true);
    }
}
