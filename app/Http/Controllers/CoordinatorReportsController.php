<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Application;
use PDF;
use App\Grade;
use App\GradingDetail;
use DB;
use Auth;
use App\Councilor;
use Carbon\Carbon;
use App\Credit;
use App\Budget;
use App\Allocation;
use App\UserAllocation;
use App\UserBudget;
class CoordinatorReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function grades()
    {
        $application = Application::join('users','student_details.user_id','users.id')
        ->join('user_councilor','users.id','user_councilor.user_id')
        ->select(DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strStudName"),'student_details.*','users.*')
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
        ->get();
        return view('SMS.Coordinator.Reports.CoordinatorReportsGrades')->withApplication($application);
    }
    public function postGrades(Request $request)
    {
        try {
            $councilor = Councilor::join('user_councilor','user_councilor.councilor_id','councilors.id')
            ->join('districts','districts.id','councilors.district_id')
            ->where('user_councilor.user_id',Auth::id())
            ->first();
            $today = Carbon::today(); 
            $application = Application::join('users','student_details.user_id','users.id')
            ->join('user_councilor','student_details.user_id','user_councilor.user_id')
            ->join('districts','student_details.district_id','districts.id')
            ->join('barangay','student_details.barangay_id','barangay.id')
            ->join('schools','student_details.school_id','schools.id')
            ->join('courses','student_details.course_id','courses.id')
            ->select('users.*','student_details.*','districts.description as districts_description','barangay.description as barangay_description','schools.description as schools_description','courses.description as courses_description',DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strUserName"))
            ->where('user_councilor.councilor_id', function($query){
                $query->from('user_councilor')
                ->join('users','user_councilor.user_id','users.id')
                ->join('councilors','user_councilor.councilor_id','councilors.id')
                ->select('councilors.id')
                ->where('users.id',Auth::id())
                ->first();
            })
            ->whereIn('users.id',$request->name)
            ->get();
            $allgrade = Grade::whereIn('student_detail_user_id',$request->name)->get();
            $number = new NumberToWord;
            foreach ($allgrade as $allgrades) {
                $user = Application::where('user_id',$allgrades->student_detail_user_id)->first();
                $credit = Credit::where('school_id',$user->school_id)->where('course_id',$user->course_id)->first();
                $allgrades->semester -= 1;
                if ($allgrades->semester < 1) {
                    $allgrades->year -= 1;
                    $allgrades->semester = $credit->semester;
                }
                $allgrades->year = $number->number($allgrades->year);
                $allgrades->semester = $number->number($allgrades->semester);
            }
            $getId = $allgrade->map(function ($allgrade) {
                return collect($allgrade->toArray())
                ->only(['id'])
                ->all();
            });
            $grade = Grade::join('grade_details','grade_details.grade_id','grades.id')
            ->whereIn('grade_details.grade_id',$getId)
            ->get();
            $grading = GradingDetail::all();
            $pdf = PDF::loadView('SMS.Coordinator.Reports.CoordinatorReportsGradesDocs', compact('grading','allgrade','grade','application','councilor','today'));
            return $pdf->stream();
        } catch(\Exception $e) {
            return redirect()->back();
        }
    }
    public function budgets()
    {
        $budget = Budget::where('user_id',Auth::id())->get();
        return view('SMS.Coordinator.Reports.CoordinatorReportsBudgets')->withBudget($budget);
    }
    public function postBudgets(Request $request)
    {
        try {
            $councilor = Councilor::join('user_councilor','user_councilor.councilor_id','councilors.id')
            ->join('districts','districts.id','councilors.district_id')
            ->where('user_councilor.user_id',Auth::id())
            ->first();
            $today = Carbon::today();
            $user = UserBudget::join('student_details','student_details.user_id','user_budget.user_id')
            ->join('users','users.id','user_budget.user_id')
            ->join('user_councilor','student_details.user_id','user_councilor.user_id')
            ->select('users.*','student_details.*',DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strUserName"),'user_budget.budget_id')
            ->where('user_councilor.councilor_id', function($query){
                $query->from('user_councilor')
                ->join('users','user_councilor.user_id','users.id')
                ->join('councilors','user_councilor.councilor_id','councilors.id')
                ->select('councilors.id')
                ->where('users.id',Auth::id())
                ->first();
            })
            ->whereIn('user_budget.budget_id',$request->checked)
            ->where('student_details.application_status','Accepted')
            ->get();
            $budget = Budget::whereIn('id',$request->checked)->get();
            $getId = $budget->map(function ($budget) {
                return collect($budget->toArray())
                ->only(['id'])
                ->all();
            });
            $allocation = Allocation::join('allocation_types','allocation_types.id','allocations.allocation_type_id')
            ->whereIn('allocations.budget_id',$getId)
            ->select('allocations.*','allocation_types.description')
            ->get();
            $allocate = UserAllocation::join('receipts','receipts.id','user_allocation.receipt_id')
            ->join('users','users.id','receipts.user_id')
            ->whereIn('user_allocation.budget_id',$getId)
            ->select('user_allocation.*','receipts.user_id',DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strUserName"))
            ->get();
            $pdf = PDF::loadView('SMS.Coordinator.Reports.CoordinatorReportsBudgetsDocs', compact('user','allocate','allocation','budget','councilor','today'))
            ->setPaper('a4','landscape');
            return $pdf->stream();
        } catch(\Exception $e) {
            return redirect()->back();
        }
    }
}
