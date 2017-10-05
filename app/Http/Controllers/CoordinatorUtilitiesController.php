<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Application;
use Auth;
use DB;
use App\Requirement;
use Response;
use App\Allocation;
use App\Grade;
use App\Studentsteps;
use App\Allocatebudget;
use App\Budgtype;
use App\UserAllocationType;
use App\Utility;
use Datatables;
class CoordinatorUtilitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function index()
    {
        $application = Application::join('users','student_details.user_id','users.id')
        ->join('user_councilor','users.id','user_councilor.user_id')
        ->select([DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strStudName"),'users.*','student_details.*'])
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
        $utility = Utility::where('user_id',Auth::id())->first();
        return view('SMS.Coordinator.Services.CoordinatorUtilities')->withApplication($application)->withUtility($utility);
    }
    public function create($id)
    {
        $grade = Grade::where('student_detail_user_id',$id)->count();
        $type = 0;
        if ($grade > 1) {
            $type = 1;
        }
        $step = Requirement::whereIn('id', function($query) use($id) {
            $query->from('user_requirement')
            ->select('requirement_id')
            ->where('user_id',$id)
            ->get();
        })
        ->where('is_active',1)
        ->where('type', $type)
        ->where('user_id',Auth::id())
        ->select('requirements.*')
        ->get();
        return Response::json($step);
    }
    public function allocation($id)
    {
        $allocation = Allocation::leftJoin('allocation_types','allocations.allocation_type_id','allocation_types.id')
        ->leftJoin('user_allocation_type','allocation_types.id','user_allocation_type.allocation_type_id')
        ->select('allocation_types.description','allocations.id','user_allocation_type.allocation_type_id')
        ->where('allocations.budget_id', function($query){
            $query->from('budgets')
            ->where('user_id',Auth::id())
            ->select('id')
            ->latest('id')
            ->first();
        })
        ->whereIn('allocations.id', function($query) use($id) {
            $query->from('user_allocation')
            ->select('allocation_id')
            ->where('user_id',$id)
            ->where('grade_id', function($subquery) use($id) {
                $subquery->from('grades')
                ->select('id')
                ->where('student_detail_user_id',$id)
                ->latest('id')
                ->first();
            })
            ->get();
        })
        ->where('user_allocation_type.user_id',Auth::id())
        ->get();
        return Response::json($allocation);
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $grade = Grade::where('student_detail_user_id',$id)->latest('id')->first();
            foreach ($request->steps as $step) {
                $steps = Studentsteps::where('user_id',$id)
                ->where('requirement_id',$step)
                ->where('grade_id',$grade->id)
                ->delete();
            }
            DB::commit();
            return Response::json($grade);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return Response::json('Input must not be nulled',500);
        }
    }
    public function stipend(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $grade = Grade::where('student_detail_user_id',$id)->latest('id')->first();
            foreach ($request->claim as $claim) {
                $allocate = Allocatebudget::where('user_id',$id)
                ->where('allocation_id',$claim)
                ->where('grade_id',$grade->id)
                ->delete();
            }
            DB::commit();
            return Response::json($grade);
        } catch (\Exception $e) {
            DB::rollBack();
            return Response::json('Input must not be nulled',500);
        }
    }
    public function question(Request $request)
    {
        $utility = new Utility;
        $utility->user_id = Auth::id();
        $utility->essay = $request->essay;
        $utility->save();
        return redirect(route('coordinatorutilities.index'));
    }
}
