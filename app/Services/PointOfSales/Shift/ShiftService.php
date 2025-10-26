<?php

namespace App\Services\PointOfSales\Shift;

use App\Models\SalesShift;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class ShiftService
{
    public function __construct()
    {
        $this->viewPermission()->editPermission()->deletePermission();
    }
    private function viewPermission(): ShiftService
    {
         if(Permission::where('name','view sales shift')->count() === 0)
         Permission::create(['name' => 'view sales shift'])->assignRole('owner');;

         return $this;
    }

    private function editPermission(): ShiftService
    {
        if(Permission::where('name','edit sales shift')->count() === 0)
            Permission::create(['name' => 'edit sales shift'])->assignRole('owner');;

        return $this;
    }

    private function deletePermission(): void
    {
        if(Permission::where('name','delete sales shift')->count() === 0)
            Permission::create(['name' => 'delete sales shift'])->assignRole('owner');
    }

    public function start($money)
    {
        return SalesShift::create([
            'start_shift' => now(),
            'user_id' => auth()->user()->id,
            'spa_id' => auth()->user()->spa_id,
            'start_money' => $money,
            'completed' => false
        ]);
    }

    public function end($spaId)
    {
        $user = auth()->user();
        $salesShift = SalesShift::where('spa_id',$spaId)
            ->where('user_id',$user->id)->where('end_shift','=',null)->where('completed','=',false)->first();
        $salesShift->end_shift = now();
        $salesShift->completed = true;
        return (bool)$salesShift->save();
    }

    public function endShiftByOwner($shiftId): bool
    {
        $salesShift = SalesShift::find($shiftId);
        $salesShift->end_shift = now();
        $salesShift->completed = true;
        return (bool)$salesShift->save();
    }


    public function abortDirectAccessToStartShiftPageIfExists()
    {
        $user = auth()->user();
        $salesShift = SalesShift::where('spa_id',$user->spa_id)
            ->where('user_id',$user->id);
        if($salesShift->where('start_shift','!=',null)
                ->where('end_shift','=',null)
                ->count() > 0)
        {
            abort(404);
        }else if($salesShift->where('completed',false)){
            abort(404);
        }
    }

    public function salesShiftLists($salesShifts)
    {
        return DataTables::of($salesShifts)
            ->editColumn('updated_at',function($salesShift){
                return $salesShift->updated_at->format('m/d/y h:m a');
            })
            ->editColumn('spa_id',function($salesShift){
                return '<span class="text-primary">'.ucwords($salesShift->spa->name).'</span>';
            })
            ->editColumn('user_id',function($salesShift){
                return $salesShift->user->full_name;
            })
            ->editColumn('start_shift',function($salesShift){
                return $salesShift->start_shift;
            })
            ->editColumn('end_shift',function($salesShift){
                return $salesShift->end_shift;
            })
            ->editColumn('start_money',function($salesShift){
                return number_format($salesShift->start_money,2);
            })
            ->editColumn('completed',function($salesShift){
                return $salesShift ? 'completed' : 'on-going';
            })
            ->addColumn('action',function($salesShift){
                $action = '';
                if(auth()->user()->can('edit sales shift'))
                {
                    $action .= '<button type="button" class="btn btn-sm bg-gradient-info m-1 mb-1 end-shift" id="'.$salesShift->id.'" title="End Shift">End Shift</button>';
                }
                if(auth()->user()->can('delete sales shift'))
                {
                    $action .= '<button type="button" class="btn btn-sm bg-gradient-orange text-white mb-1 m-1 delete-shift" id="'.$salesShift->id.'" title="Delete Shift">Delete</button>';
                }
                return $action;
            })
            ->rawColumns(['action','spa_id'])
            ->make(true);
    }
}
