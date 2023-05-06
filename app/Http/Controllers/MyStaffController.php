<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Spa;
use App\Services\StaffService;
use Illuminate\Support\Facades\Validator;
use Config;

class MyStaffController extends Controller
{
    private $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->middleware('check.if.user.is.owner')->only(['get_owner_staffs', 'my_staffs', 'store', 'show', 'update', 'destroy']);

        $this->staffService = $staffService;
    }

    public function get_owner_staffs()
    {
        return $this->staffService->staffs(auth()->user()->owner->id);
    }

    public function my_staffs()
    {
        $offer_type = Config::get('app.offer_type');
        $certificate_type = Config::get('app.certificate_type');

        return view('Owner.staff.index', ['offer_type' => $offer_type, 'certificate_type' => $certificate_type]);
    }

    public function store(Request $request)
    {
        return $this->staffService->create($request);
    }

    public function show($id)
    {
        return $this->staffService->show($id);
    }

    public function update(Request $request, $id)
    {
        return $this->staffService->edit($request, $id);
    }

    public function destroy($id)
    {
        return $this->staffService->delete($id);
    }
}
