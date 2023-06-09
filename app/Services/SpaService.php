<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Spa;
use Yajra\DataTables\Facades\DataTables;

class SpaService
{
    public function spas($spa)
    {
        return DataTables::of($spa)
            ->editColumn('created_at',function($spa){
                return $spa->created_at->format('M d, Y');
            })
            ->addColumn('name',function ($spa){

                return '<a href="'.route('spa.show',['spa' => $spa->id]).'">'.ucwords($spa->name).'</a>';
            })
            ->addColumn('address',function ($spa){
                return $spa->address;
            })
            ->addColumn('action', function($spa){
                $action = "";
                if(auth()->user()->can('view spa'))
                {
                    $action .= '<a href="'.route('spa.show',['spa' => $spa->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                if(auth()->user()->can('edit spa'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-spa-btn" id="'.$spa->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete spa'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-spa-btn" id="'.$spa->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                if(auth()->user()->can('download attendance'))
                {
                    $action .= '<a href="/download/'.urldecode($spa->name).'" class="btn btn-sm btn-outline-warning" id="'.$spa->name.'"><i class="fas fa-fw fa-file-download"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action','name'])
            ->make(true);
    }

    public function spa_services($id)
    {
        $service = Service::where('spa_id', $id)->get();
        return DataTables::of($service)
            ->editColumn('created_at',function($service){
                return $service->created_at->format('M d, Y');
            })
            ->addColumn('name',function ($service){
                if(auth()->user()->can('view service'))
                {
                    return '<a href="#" title="View">'.ucfirst($service->name).'</a>&nbsp;';
                } else {
                    return ucfirst($service->name);
                }
            })
            ->editColumn('description',function($service){
                return $service->description;
            })
            ->editColumn('price',function($service){
                return number_format($service->price,2);
            })
            ->addColumn('duration',function ($service){
                return $service->duration.' mins.';
            })
            ->addColumn('category',function ($service){
                return ucfirst($service->category);
            })
            ->addColumn('action', function($service){
                $action = "";
                if(auth()->user()->can('view service'))
                {
                    $action .= '<a href="'.route('spa.overview',['id' => $service->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                if(auth()->user()->can('edit service'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-service-btn" id="'.$service->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete service'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-service-btn" id="'.$service->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action','name'])
            ->make(true);
    }

    public function get_spa_lists($id)
    {
        $spa = Spa::where('owner_id', $id)->orderBy('name' , 'ASC')->pluck('id', 'name');
        return $spa;
    }

    public function spa_info($id)
    {
        $spa = Spa::where('id', $id)->first();

        return $spa;
    }
}
