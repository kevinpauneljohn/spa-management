<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Spa;
use App\Models\Therapist;
class TherapistController extends Controller
{
    public function lists($id)
    {

        $therapist = Therapist::where('spa_id', $id)->get();
        return DataTables::of($therapist)
            ->editColumn('created_at',function($therapist){
                return $therapist->created_at->format('M d, Y');
            })
            ->addColumn('fullname',function ($therapist){
                if(auth()->user()->can('view therapist'))
                {
                    return '<a href="'.route('spa.overview',['id' => $therapist->id]).'" title="View">'.$therapist->firstname.' '.$therapist->lastname.'</a>&nbsp;';
                } else {
                    return $therapist->firstname.' '.$therapist->lastname;
                }
            })
            ->editColumn('date_of_birth',function($therapist){
                return $therapist->created_at->format('F d, Y');
            })
            ->addColumn('mobile_number',function ($therapist){
                return $therapist->mobile_number;
            })
            ->addColumn('email',function ($therapist){
                return $therapist->email;
            })
            ->addColumn('gender',function ($therapist){
                return $therapist->gender;
            })
            ->addColumn('action', function($therapist){
                $action = "";
                if(auth()->user()->can('view therapist'))
                {
                    $action .= '<a href="'.route('spa.overview',['id' => $therapist->id]).'" class="btn btn-sm btn-outline-success" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                if(auth()->user()->can('edit therapist'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-primary edit-therapist-btn" id="'.$therapist->id.'"><i class="fa fa-edit"></i></a>&nbsp;';
                }
                if(auth()->user()->can('delete therapist'))
                {
                    $action .= '<a href="#" class="btn btn-sm btn-outline-danger delete-therapist-btn" id="'.$therapist->id.'"><i class="fa fa-trash"></i></a>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action','fullname'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $id = $request['spa_id'];
        $firstname = $request['firstname'];
        $middlename = $request['middlename'];
        $lastname = $request['lastname'];
        $date_of_birth = $request['date_of_birth'];
        $mobile_number = $request['mobile_number'];
        $email = $request['email'];
        $gender = $request['gender'];
        $certificate = $request['certificate'];
        $commission = $request['commission'];
        $allowance = $request['allowance'];
        $offer_type = $request['offer_type'];

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'commission' => 'required',
            'offer_type' => 'required'
        ]);

        if($validator->passes())
        {
            $code = 201;
            $therapist = Therapist::create([
                'spa_id' => $id,
                'firstname' => $firstname,
                'middlename' => $middlename,
                'lastname' => $lastname,
                'date_of_birth' => $date_of_birth,
                'mobile_number' => $mobile_number,
                'email' => $email,
                'gender' => $gender,
                'certificate' => $certificate,
                'commission' => $commission,
                'allowance' => $allowance,
                'offer_type' => $offer_type
            ]);
            
            $response = [
                'status'   => true,
                'message'   => 'Therapist information successfully saved.',
                'data'      => $therapist,
            ];    
            
            return response($response, $code);
        } else {
            return response()->json($validator->errors());
        }
    }

    public function show($id)
    {
        $therapist = Therapist::findOrFail($id);
        return response()->json(['therapist' => $therapist]);
    }

    public function update(Request $request, $id)
    {
        $firstname = $request['firstname'];
        $middlename = $request['middlename'];
        $lastname = $request['lastname'];
        $date_of_birth = $request['date_of_birth'];
        $mobile_number = $request['mobile_number'];
        $email = $request['email'];
        $gender = $request['gender'];
        $certificate = $request['certificate'];
        $commission = $request['commission'];
        $allowance = $request['allowance'];
        $offer_type = $request['offer_type'];

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'commission' => 'required',
            'offer_type' => 'required'
        ]);

        if($validator->passes())
        {
            $therapist = Therapist::findOrFail($id);
            $therapist->firstname = $firstname;
            $therapist->middlename = $middlename;
            $therapist->lastname = $lastname;
            $therapist->date_of_birth = $date_of_birth;
            $therapist->mobile_number = $mobile_number;
            $therapist->email = $email;
            $therapist->gender = $gender;
            $therapist->certificate = $certificate;
            $therapist->commission = $commission;
            $therapist->allowance = $allowance;
            $therapist->offer_type = $offer_type;

            if($therapist->isDirty()){
                $therapist->save();
                return response()->json(['status' => true, 'message' => 'Therapist information successfully updated.']);
            } else {
                return response()->json(['status' => false, 'message' => 'No changes made.']);
            } 
        }
        return response()->json($validator->errors());
    }

    public function destroy($id)
    {
        $therapist = Therapist::findOrFail($id);

        $status = false;
        $message = 'Therapist information could not be deleted.';
        if ($therapist->delete()) {
            $status = true;
            $message = 'Therapist information successfully deleted.';
        }

        return response()->json(['status' => $status, 'message' => $message]);
    }
}
