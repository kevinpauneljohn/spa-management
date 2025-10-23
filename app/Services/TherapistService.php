<?php

namespace App\Services;

use App\Models\Spa;
use App\Models\Therapist;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use App\Services\TransactionService;
use App\Services\RoomService;

class TherapistService
{
    private $transactionService;
    private $roomService;

    public $grossCommission;
    public $grossSales;
    public $dateFrom;
    public $dateTo;

    public function __construct(TransactionService $transactionService, RoomService $roomService, Request $request)
    {
        $this->transactionService = $transactionService;
        $this->roomService = $roomService;
        $this->dateFrom = $request->session()->get('transactionsDateFrom');
        $this->dateTo = $request->session()->get('transactionsDateTo');
    }

    public function all_therapist_thru_spa($therapist)
    {
        return DataTables::of($therapist)
            ->editColumn('checkbox',function($therapist){
                return '<div class="form-check"><input type="checkbox" name="exclude_therapists" class="form-check-input exclude_therapists" id="exclude_therapists" value="'.$therapist->id.'"></div>';
            })
            ->editColumn('created_at',function($therapist){
                return $therapist->created_at->format('M d, Y');
            })
            ->addColumn('fullname',function ($therapist){
                if(auth()->user()->can('view therapist'))
                {
                    return '<a href="'.route('therapists.profile',['id' => $therapist->id]).'" title="View">'.ucwords($therapist->user->fullname).'</a>&nbsp;';
                } else {
                    return ucwords($therapist->user->fullname);
                }
            })
            ->editColumn('date_of_birth',function($therapist){
                return $therapist->created_at->format('F d, Y');
            })
            ->addColumn('mobile_number',function ($therapist){
                return $therapist->user->mobile_number;
            })
            ->addColumn('email',function ($therapist){
                return $therapist->user->email;
            })
            ->addColumn('gender',function ($therapist){
                return $therapist->gender;
            })
            ->editColumn('is_excluded',function ($therapist){
                return $therapist->is_excluded ? '<span class="text-danger">Yes</span>' : '';
            })
            ->setRowClass(function($therapist){
                return $therapist->is_excluded ? 'excluded' : '';
            })
            ->addColumn('action', function($therapist){
                $action = "";
                if(auth()->user()->can('view therapist'))
                {
                    $action .= '<a href="'.route('therapists.profile',['id' => $therapist->id]).'" class="btn btn-sm bg-gradient-success m-1" title="View"><i class="fas fa-eye"></i></a>&nbsp;';
                }
                if(auth()->user()->can('edit therapist'))
                {
                    $user = $this->get_therapist_user_id($therapist->mobile_number, $therapist->email);
                    $user_id = '';
                    if (!empty($user)) {
                        $user_id = $user->id;
                    }
                    $action .= '<button class="btn btn-sm bg-gradient-info m-1 edit-therapist-btn" id="'.$therapist->id.'" data-user_id="'.$user_id.'"><i class="fa fa-edit"></i></button>&nbsp;';
                }
                if(auth()->user()->can('delete therapist'))
                {
                    $action .= '<button class="btn btn-sm bg-gradient-orange m-1 text-white delete-therapist-btn" id="'.$therapist->id.'"><i class="fa fa-trash"></i></button>&nbsp;';
                }
                return $action;
            })
            ->rawColumns(['action','fullname','checkbox','is_excluded'])
            ->make(true);
    }



    private function get_therapist_user_id($mobile, $email)
    {
        $user = User::where([
            'mobile_number' => $mobile,
            'email' => $email,
        ])->first();

        return $user;
    }

    public function get_therapist_by_id($therapist_id)
    {
        return Therapist::findOrfail($therapist_id);
    }

    //
    public function offer_type_filter(array $request): array
    {
        if($request['offer_type'] === 'percentage_only')
        {
            $request['commission_flat'] = null;
            $request['allowance'] = null;
        }
        elseif($request['offer_type'] === 'percentage_plus_allowance')
        {
            $request['commission_flat'] = null;
        }
        elseif($request['offer_type'] === 'amount_only')
        {
            $request['commission_percentage'] = null;
            $request['allowance'] = null;
        }
        elseif($request['offer_type'] === 'amount_plus_allowance')
        {
            $request['commission_percentage'] = null;
        }

        return $request;
    }

    public function delete_therapist($id): bool
    {
        $therapist = Therapist::findOrFail($id);
        if($therapist->user->delete() && $therapist->delete()) return true;

        return false;
    }

    public function therapistTransactionsCount($spa_id, $dateTime)
    {
        $data = [
            'therapist' => $this->getTherapistList($spa_id, $dateTime),
            'rooms' => $this->roomService->getRoomList($spa_id, $dateTime),
            'services' => $this->getServices($spa_id),
        ];

        return $data;
    }

    public function getTherapistList($spa_id, $dateTime)
    {
        $therapist = Therapist::where('spa_id', $spa_id)->get();

        $data = [];
        if (!empty($therapist)) {
            foreach ($therapist as $list) {
                $count_transactions = $this->transactionService->therapistCount($list->id);
                $is_available = $this->transactionService->therapistAvailability($spa_id, $list->id, $dateTime);
                $data [] = [
                    'therapist_id' => $list->id,
                    'fullname' => $list->user->fullname,
                    'count' => $count_transactions,
                    'availability' => $is_available ? 'yes' : 'no',
                ];
            }

            array_multisort(array_column($data, 'count'), $data);
        }

        return $data;
    }

    public function getServices($spa_id)
    {
        return Service::where('spa_id', $spa_id)->pluck('id', 'name');
    }

    public function getSales($query, $spa)
    {

        return DataTables::of($query)
            ->editColumn('commission_percentage',function($therapist){
                return $therapist->commission_percentage !== null ? $therapist->commission_percentage.'%' : '';
            })
            ->editColumn('commission_flat',function($therapist){
                return $therapist->commission_flat !== null ? number_format($therapist->commission_flat,2) : '';
            })
            ->addColumn('therapist',function($therapist){
                return '<a href="'.route('therapists.profile',['id' => $therapist->id]).'">'.$therapist->fullName.'</a>';
            })
            ->addColumn('total_clients',function ($therapist){

                return $therapist->transactions()->whereDate('start_time','>=',$this->dateFrom)
                        ->whereDate('start_time','<=',$this->dateTo)->count() +
                    $therapist->transactionsTherapistTwo()->whereDate('start_time','>=',$this->dateFrom)
                        ->whereDate('start_time','<=',$this->dateTo)->count();
            })
            ->addColumn('gross_sales',function($therapist){
                return '<span class="text-info">'.number_format($therapist->grossSales($this->dateFrom, $this->dateTo),2).'</span>';
            })
            ->addColumn('gross_commission',function ($therapist){
                return '<span class="text-primary text-bold">'.number_format($therapist->grossSalesCommission($this->dateFrom, $this->dateTo),2).'</span>';
            })
            ->addColumn('summary', function($therapist){
                return '<button type="button" class="btn btn-sm btn-outline-info rounded view-summary" id="'.$therapist->id.'" title="View Summary"><i class="fas fa-file-invoice"></i></button>';
            })
            ->rawColumns(['gross_sales','therapist','summary','gross_commission'])
            ->with([
                'total_clients' => number_format(
                    $spa->displayTransactionsTherapistOneFromDateRange($this->dateFrom, $this->dateTo)->count() +
                    $spa->displayTransactionsTherapistTwoFromDateRange($this->dateFrom, $this->dateTo)->count()
                    ,0),
                'total_gross_sales' => number_format($this->grossSales = collect($spa->displayTransactionsTherapistOneFromDateRange($this->dateFrom, $this->dateTo)->get())
                    ->concat($spa->displayTransactionsTherapistTwoFromDateRange($this->dateFrom, $this->dateTo)->get())->map(function($item, $key){
                        return $item['therapist_2'] == null ? $item['commission_reference_amount'] : $item['commission_reference_amount'] / 2;
                    })->sum(), 2),
                'total_gross_sales_commissions' => $this->grossCommission = collect($spa->therapists)->map(function($item, $key){
                    return $item->grossSalesCommission($this->dateFrom, $this->dateTo);
                })->sum(),
                'total_gross_sales_commissions_formatted' => number_format($this->grossCommission,2),
                'net_sales' => number_format(
                    $this->grossSales
                    - $this->grossCommission,2)
            ])
            ->make(true);
    }

    public function selected_therapists(array $therapists)
    {
        return Therapist::with('user')->whereIn('id',$therapists)->get();
    }

    public function excluded_therapists(array $therapists, bool $exclude): bool
    {
        if(collect($therapists)->count() > 0)
        {
            return DB::table('therapists')->whereIn('id',$therapists)->update([
                    'is_excluded' => $exclude
                ]) > 0;
        }
        return false;
    }
}
