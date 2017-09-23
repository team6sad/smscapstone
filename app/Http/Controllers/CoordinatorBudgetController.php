<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Allocation;
use App\Budget;
use Carbon\Carbon;
use Response;
use Datatables;
use Config;
use App\Connection;
use App\Budgtype;
use Auth;
use App\Application;
use App\Allocatebudget;
use DB;
use App\Utility;
use App\UserBudget;
use App\Credit;
use App\Grade;
class CoordinatorBudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function data()
    {   
        $budget = Budget::where('user_id',Auth::id());
        return Datatables::of($budget)
        ->addColumn('action', function ($data) {
            $userbudget = UserBudget::where('budget_id',$data->id)->count();
            if ($userbudget == 0) {
                return "<button class='btn btn-info btn-xs btn-view' value='$data->id'><i class='fa fa-eye'></i> View</button> <button class='btn btn-warning btn-xs btn-detail open-modal' value='$data->id'><i class='fa fa-edit'></i> Edit</button>";
            } else {
                return "<button class='btn btn-info btn-xs btn-view' value='$data->id'><i class='fa fa-eye'></i> View</button>";
            }
        })
        ->editColumn('budget_date', function ($data) {
            return $data->budget_date ? with(new Carbon($data->budget_date))->format('M d, Y - h:i A') : '';
        })
        ->setRowId(function ($data) {
            return $data = 'id'.$data->id;
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    public function index()
    {
        $utility = Utility::where('user_id',Auth::id())->first();
        $budgtype = Budgtype::where('is_active',1)->get();
        return view('SMS.Coordinator.Services.CoordinatorBudget')->withBudgtype($budgtype)->withUtility($utility);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $budget = Budget::where('user_id',Auth::id())
            ->latest('id')->first();
            if($budget!=null) {
                $application = Application::join('users','student_details.user_id','users.id')
                ->join('user_councilor','users.id','user_councilor.user_id')
                ->select('student_details.*')
                ->where('users.type','Student')
                ->where('user_councilor.councilor_id', function($query){
                    $query->from('user_councilor')
                    ->join('users','user_councilor.user_id','users.id')
                    ->join('councilors','user_councilor.councilor_id','councilors.id')
                    ->select('councilors.id')
                    ->where('user_councilor.user_id',Auth::id())
                    ->first();
                })
                ->where('student_details.application_status','Accepted')
                ->where('student_status','Continuing')
                ->get();
                foreach ($application as $applications) {
                    $credit = Credit::where('school_id',$applications->school_id)->where('course_id',$applications->course_id)->first();
                    $grade = Grade::where('student_detail_user_id',$applications->user_id)->latest('id')->first();
                    if ($grade->year == $credit->year && $grade->semester == $credit->semester) {
                        $applications->student_status = 'Graduated';
                        $applications->save();
                    }
                }
            }
            $connection = Connection::where('user_id',Auth::id())->first();
            $ctr = 0;
            $budget = new Budget;
            $budget->user_id=Auth::id();
            $budget->councilor_id = $connection->councilor_id;
            $budget->amount=$request->budget_amount;
            $budget->budget_per_student=$request->budget_per_student;
            $budget->slot_count=$request->slot_count;
            $budget->budget_date=Carbon::now(Config::get('app.timezone'));
            $budget->save();
            foreach ($request->amount as $amount) {
                $allocation = new Allocation;
                $allocation->budget_id = $budget->id;
                $allocation->allocation_type_id = $request->id[$ctr];
                $allocation->amount = $amount;
                $allocation->save();
                $ctr++;
            }
            $utility = Utility::find(Auth::id());
            $utility->phase_status = 1;
            $utility->save();
            DB::commit();
            return Response::json($utility);
        } catch(\Exception $e) {
            DB::rollBack();
            return Response::json($e->getMessage(),500);
        } 
    }
    public function show($id)
    {
        $allocation = Allocation::join('budgets','allocations.budget_id','budgets.id')
        ->join('allocation_types','allocations.allocation_type_id','allocation_types.id')
        ->select(DB::raw("DATE_FORMAT(budget_date, '%M %d, %Y') as date"),'budgets.*','allocations.amount as allocation_amount','allocation_types.*')
        ->where('allocations.budget_id',$id)
        ->get();
        return Response::json($allocation);
    }
    public function edit($id)
    {
        try {
            $userbudget = UserBudget::where('budget_id',$id)->firstorfail();
            return Response::json('Budget already changed',500);
        } catch(\Exception $e) {
            $allocation = Allocation::join('budgets','allocations.budget_id','budgets.id')
            ->join('allocation_types','allocations.allocation_type_id','allocation_types.id')
            ->select('budgets.*','allocations.amount as allocation_amount','allocation_types.id as allocation_id','allocations.id as allocate_id')
            ->where('allocations.budget_id',$id)
            ->get();
            return Response::json($allocation);
        } 
    }
    public function end()
    {
        try {
            $budget = UserBudget::where('budget_id', function($query) {
                $query->from('budgets')
                ->latest('id')
                ->select('id')
                ->first();
            })->firstorfail();
            DB::beginTransaction();
            try {
                $application = Application::where('is_new',1)->get();
                foreach ($application as $applications) {
                    $applications->is_new = 0;
                    $applications->save();
                }
                $utility = Utility::find(Auth::id());
                $utility->phase_status = 0;
                $utility->save();
                DB::commit();
                return Response::json($utility);
            } catch(\Exception $e) {
                DB::rollBack();
                return Response::json($e->getMessage(),500);
            } 
        } catch(\Exception $e) {
            return Response::json($e->getMessage(),500);
        } 
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $connection = Connection::where('user_id',Auth::id())->first();
            $ctr = 0;
            $budget = Budget::find($id);
            $budget->councilor_id = $connection->councilor_id;
            $budget->amount=$request->budget_amount;
            $budget->budget_per_student=$request->budget_per_student;
            $budget->slot_count=$request->slot_count;
            $budget->budget_date=Carbon::now(Config::get('app.timezone'));
            $budget->save();
            foreach ($request->amount as $amount) {
                $allocation = Allocation::find($request->allocation_id[$ctr]);
                $allocation->budget_id = $budget->id;
                $allocation->allocation_type_id = $request->id[$ctr];
                $allocation->amount = $amount;
                $allocation->save();
                $ctr++;
            }
            DB::commit();
            return Response::json($budget);
        } catch(\Exception $e) {
            DB::rollBack();
            return Response::json($e->getMessage(),500);
        } 
    }
    public function getBudget()
    {
        $budget = Budget::where('user_id',Auth::id())
        ->latest('id')->first();
        $userbudget = UserBudget::where('budget_id',$budget->id)->count();
        $allocation = Allocatebudget::join('allocations','user_allocation.allocation_id','allocations.id')
        ->whereIn('allocation_id', function($query) use($budget) {
            $query->from('allocations')
            ->where('budget_id', $budget->id)
            ->select('id')
            ->get();
        })
        ->select('allocations.amount')
        ->get();
        if($budget==null)
            $budget = (object)['amount' => 0, 'slot_count' => 0];
        else {
            $budget->slot_count -= $userbudget;
            foreach ($allocation as $allocations) {
                $budget->amount -= $allocations->amount;
            }
        }
        return Response::json($budget);
    }
}