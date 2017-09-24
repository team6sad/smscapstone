<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\User;
use App\Application;
use App\District;
use App\Councilor;
use App\Barangay;
use App\Course;
use App\School;
use App\Batch;
use Response;
use Auth;
use Datatables;
use App\Grade;
use App\Requirement;
use App\Studentsteps;
use App\Budgtype;
use App\Allocation;
use Carbon\Carbon;
use Config;
use App\Allocatebudget;
use App\Credit;
use App\UserBudget;
class CoordinatorScholarsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function index()
    {
        return view('SMS.Coordinator.Scholar.List.CoordinatorStudentsList');
    }
    public function store(Request $request)
    {
        $application = Application::join('users','student_details.user_id','users.id')
        ->join('user_councilor','users.id','user_councilor.user_id')
        ->select([DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strStudName"),'student_details.*','users.*'])
        ->where('users.type','Student')
        ->where('user_councilor.councilor_id', function($query){
            $query->from('user_councilor')
            ->join('users','user_councilor.user_id','users.id')
            ->join('councilors','user_councilor.councilor_id','councilors.id')
            ->select('councilors.id')
            ->where('user_councilor.user_id',Auth::id())
            ->first();
        })
        ->where('student_details.student_status',$request->status)
        ->where('student_details.application_status','Accepted');
        $datatables = Datatables::of($application)
        ->addColumn('counter', function ($data) {
            if ($data->student_status == 'Continuing') {
                $grade = Grade::where('student_detail_user_id',$data->id)->count();
                $type = 0;
                if ($grade > 1) {
                    $type = 1;
                }
                $count = Requirement::where('is_active',1)
                ->where('type',$type)
                ->where('user_id',Auth::id())
                ->count();
                $steps = Studentsteps::join('requirements','user_requirement.requirement_id','requirements.id')
                ->where('user_requirement.user_id',$data->id)
                ->where('requirements.type',$type)
                ->where('requirements.user_id',Auth::id())
                ->where('user_requirement.budget_id', function($query) {
                    $query->from('budgets')
                    ->where('user_id', Auth::id())
                    ->latest('id')
                    ->select('id')
                    ->first();
                })
                ->count();
                if($count!=0)
                    $percentage = (($steps/$count)*100);
                else
                    $percentage = 0;
                return "<div class='pull-right' style='margin-top: -5px; margin-left: 5px;'>$steps/$count </div></div><div class='progress progress-sm active'>
                <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: $percentage%'></div>";
            } else {
                return "<div class='pull-right' style='margin-top: -5px; margin-left: 5px;'>0/0 </div></div><div class='progress progress-sm active'>
                <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0%'></div>";
            }
        })
        ->addColumn('stipend', function ($data) {
            if ($data->student_status == 'Continuing') {
                $count = Budgtype::count();
                $allocate = Allocation::leftjoin('user_allocation','allocations.id','user_allocation.allocation_id')
                ->where('user_allocation.user_id',$data->id)
                ->where('user_allocation.budget_id', function($query) {
                    $query->from('budgets')
                    ->where('user_id', Auth::id())
                    ->latest('id')
                    ->select('id')
                    ->first();
                })->count();
                if($count!=0)
                    $percentage = (($allocate/$count)*100);
                else
                    $percentage = 0;
                return "<div class='pull-right' style='margin-top: -5px; margin-left: 5px;'>$allocate/$count </div></div><div class='progress progress-sm active'>
                <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: $percentage%'></div>";
            } else {
                return "<div class='pull-right' style='margin-top: -5px; margin-left: 5px;'>0/0 </div></div><div class='progress progress-sm active'>
                <div class='progress-bar progress-bar-success progress-bar-striped' role='progressbar' aria-valuenow='0' aria-valuemin='0' aria-valuemax='100' style='width: 0%'></div>";
            }
        })
        ->editColumn('checkbox', function ($data) {
            $checked = '';
            if($data->is_active==1){
                $checked = 'checked';
            }
            return "<input type='checkbox' id='isActive' name='isActive' value='$data->user_id' data-toggle='toggle' data-style='android' data-onstyle='success' data-offstyle='danger' data-on=\"<i class='fa fa-check-circle'></i> Active\" data-off=\"<i class='fa fa-times-circle'></i> Inactive\" $checked data-size='mini'><script>
            $('[data-toggle=\'toggle\']').bootstrapToggle('destroy');   
            $('[data-toggle=\'toggle\']').bootstrapToggle();</script>";
        })
        ->addColumn('action', function ($data) {
            return "<a href=".route('scholars.show',$data->user_id)."><button class='btn btn-info btn-xs open-modal' value='$data->user_id'><i class='fa fa-eye'></i> View</button></a>";
        })
        ->editColumn('strStudName', function ($data) {
            $images = url('images/'.$data->picture);
            return "<table><tr><td><div class='col-md-2'><img src='$images' class='img-circle' alt='User Image' height='40'></div></td><td>$data->last_name, $data->first_name $data->middle_name</td></tr></table>";
        })
        ->setRowId(function ($data) {
            return $data = 'id'.$data->user_id;
        })
        ->rawColumns(['strStudName','checkbox','action','stipend','counter','student_status']);
        return $datatables->make(true);
    }
    public function checkbox(Request $request, $id)
    {
        try {
            $user = User::findorfail($id);
            $user->is_active = $request->is_active;
            $user->save();
        } catch(\Exception $e) {
            return "Deleted";
        } 
    }
    public function show($id)
    {
        try {
            $application = Application::join('users','student_details.user_id','users.id')
            ->join('schools','student_details.school_id','schools.id')
            ->join('districts','student_details.district_id','districts.id')
            ->join('barangay','student_details.barangay_id','barangay.id')
            ->join('courses','student_details.course_id','courses.id')
            ->select(DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strStudName"),'student_details.*','users.*','schools.description as school','districts.description as district','barangay.description as barangay','courses.description as course',DB::raw("DATE_FORMAT(student_details.birthday, '%M %d, %Y') as date"))
            ->where('users.id',$id)
            ->firstorfail();
            $grade = Grade::where('student_detail_user_id',$id)->count();
            $type = 0;
            if ($grade > 1) {
                $type = 1;
            }
            $getsem = UserBudget::where('user_id',$id)->where('budget_id', function($query) {
                $query->from('budgets')
                ->where('user_id', Auth::id())
                ->latest('id')
                ->select('id')
                ->first();
            })->count();
            $requirement = Requirement::whereNotIn('id', function($query) use($id) {
                $query->from('user_requirement')
                ->where('budget_id', function($subquery) {
                    $subquery->from('budgets')
                    ->where('user_id', Auth::id())
                    ->latest('id')
                    ->select('id')
                    ->first();
                })
                ->select('requirement_id')
                ->where('user_id',$id)
                ->get();
            })
            ->where('is_active',1)
            ->where('type', $type)
            ->where('user_id',Auth::id())
            ->select('requirements.*')
            ->get();
            $credit = Credit::where('school_id',$application->school_id)->where('course_id',$application->course_id)->first();
            $grade = Grade::where('student_detail_user_id',$id)
            ->latest('id')
            ->first();
            $grade->semester += 1;
            if ($grade->semester > $credit->semester) {
                $grade->semester = 1;
                $grade->year += 1;
            }
            $number = new NumberToWord;
            $grade->year = $number->number($grade->year);
            $grade->semester = $number->number($grade->semester);
            $oldgrade = Grade::join('user_requirement','user_requirement.grade_id','grades.id')
            ->join('requirements','user_requirement.requirement_id','requirements.id')
            ->where('user_requirement.budget_id', '!=', function($query) {
                $query->from('budgets')
                ->where('user_id', Auth::id())
                ->latest('id')
                ->select('id')
                ->first();
            })
            ->where('grades.student_detail_user_id',$id)
            ->select('requirements.description',DB::raw("DATE_FORMAT(user_requirement.date_passed, '%M %d, %Y') as date_passed"),'grades.year','grades.semester','user_requirement.grade_id')
            ->get();
            foreach ($oldgrade as $oldgrades) {
                $oldgrades->year = $number->number($oldgrades->year);
                $oldgrades->semester = $number->number($oldgrades->semester);
            }
            $allgrade = Grade::where('student_detail_user_id',$id)
            ->where('id', '!=', $grade->id)
            ->get();
            foreach ($allgrade as $allgrades) {
                $allgrades->semester += 1;
                if ($allgrades->semester > $credit->semester) {
                    $allgrades->semester = 1;
                    $allgrades->year += 1;
                }
                $allgrades->year = $number->number($allgrades->year);
                $allgrades->semester = $number->number($allgrades->semester);
            }
            $oldallocation = Grade::join('user_allocation','user_allocation.grade_id','grades.id')
            ->join('allocations','allocations.id','user_allocation.allocation_id')
            ->join('allocation_types','allocation_types.id','allocations.allocation_type_id')
            ->where('user_allocation.budget_id', '!=', function($query) {
                $query->from('budgets')
                ->where('user_id', Auth::id())
                ->latest('id')
                ->select('id')
                ->first();
            })
            ->where('grades.student_detail_user_id',$id)
            ->select(DB::raw("DATE_FORMAT(user_allocation.date_claimed, '%M %d, %Y %h:%i %a') as date_claimed"),'user_allocation.grade_id','allocation_types.description')
            ->get();
            foreach ($oldallocation as $oldallocations) {
                $oldallocations->year = $number->number($oldallocations->year);
                $oldallocations->semester = $number->number($oldallocations->semester);
            }
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
            ->whereNotIn('allocations.id', function($query) use($id) {
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
            $count = Requirement::where('is_active',1)
            ->where('type',$type)
            ->where('user_id',Auth::id())
            ->count();
            $studentstep = Studentsteps::join('requirements','user_requirement.requirement_id','requirements.id')
            ->where('user_requirement.user_id',$id)
            ->where('requirements.type',$type)
            ->where('requirements.user_id',Auth::id())
            ->where('user_requirement.budget_id', function($query) {
                $query->from('budgets')
                ->where('user_id', Auth::id())
                ->latest('id')
                ->select('id')
                ->first();
            })
            ->count();
            return view('SMS.Coordinator.Scholar.List.CoordinatorStudentsListDetails')->withApplication($application)->withRequirement($requirement)->withGrade($grade)->withOldgrade($oldgrade)->withAllgrade($allgrade)->withAllocation($allocation)->withOldallocation($oldallocation)->withCount($count)->withStudentstep($studentstep)->withGetsem($getsem);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('scholars.index');
        }
    }
    public function requirements(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $userbudget = UserBudget::where('user_id',$id)->where('budget_id', function($query) {
                $query->from('budgets')
                ->where('user_id',Auth::id())
                ->select('id')
                ->latest('id')
                ->first();
            })->first();
            $grade = Grade::where('student_detail_user_id',$id)->latest('id')->first();
            foreach ($request->steps as $step) {
                $steps = new Studentsteps;
                $steps->user_id = $id;
                $steps->requirement_id = $step;
                $steps->budget_id = $userbudget->budget_id;
                $steps->grade_id = $grade->id;
                $steps->date_passed = Carbon::now(Config::get('app.timezone'));
                $steps->save();
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
    public function stipend(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $userbudget = UserBudget::where('user_id',$id)->where('budget_id', function($query) {
                $query->from('budgets')
                ->where('user_id',Auth::id())
                ->select('id')
                ->latest('id')
                ->first();
            })->first();
            $grade = Grade::where('student_detail_user_id',$id)->latest('id')->first();
            foreach ($request->claim as $claim) {
                $allocate = new Allocatebudget;
                $allocate->user_id = $id;
                $allocate->allocation_id = $claim;
                $allocate->grade_id = $grade->id;
                $allocate->budget_id = $userbudget->budget_id;
                $allocate->date_claimed = Carbon::now(Config::get('app.timezone'));
                $allocate->save();
            }
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
        }
    }
}
