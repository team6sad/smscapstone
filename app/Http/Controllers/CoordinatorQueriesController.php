<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\Councilor;
use PDF;
use Auth;
use Carbon\Carbon;
use App\School;
use App\Course;
use App\Batch;
use App\Application;
use DB;
use App\Barangay;
use App\Budget;
use App\Grade;
use App\GradingDetail;
use App\UserBudget;
use App\Budgtype;
use App\Allocation;
use App\UserAllocation;
class CoordinatorQueriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function events()
    {
        return view('SMS.Coordinator.Queries.CoordinatorQueriesEvents');
    }
    public function postEvents(Request $request)
    {
        try {
            $date_from = Carbon::createFromFormat('Y-m-d', $request->date_from);
            $date_to = Carbon::createFromFormat('Y-m-d', $request->date_to);
            $councilor = Councilor::join('user_councilor','user_councilor.councilor_id','councilors.id')
            ->join('districts','districts.id','councilors.district_id')
            ->where('user_councilor.user_id',Auth::id())
            ->first();
            $today = Carbon::today();
            $event = Event::where('user_id',Auth::id())
            ->whereBetween('date_held',[$date_from,$date_to]);
            if (!is_null($request->status)) {
                $event = $event->whereIn('status',$request->status);
            }
            $event = $event->get();
            $pdf = PDF::loadView('SMS.Coordinator.Queries.CoordinatorQueriesEventsDocs', compact('event','councilor','today'));
            return $pdf->stream();
        } catch(\Exception $e) {
            return redirect()->back();
        }
    }
    public function students()
    {
        try {
            $barangay = Barangay::where('is_active',1)->where('district_id', function($query) {
                $query->from('councilors')
                ->join('user_councilor','user_councilor.councilor_id','councilors.id')
                ->join('districts','districts.id','councilors.district_id')
                ->where('user_councilor.user_id',Auth::id())
                ->select('districts.id')
                ->first();
            })
            ->select('barangay.*')
            ->get();
            $school = School::join('user_school','schools.id','user_school.school_id')
            ->where('user_school.user_id', Auth::id())
            ->select('schools.*')
            ->get();
            $course = Course::join('user_course','courses.id','user_course.course_id')
            ->where('user_course.user_id', Auth::id())
            ->select('courses.*')
            ->get();
            $batch = Batch::where('is_active',1)->get();
            return view('SMS.Coordinator.Queries.CoordinatorQueriesStudents')->withSchool($school)->withCourse($course)->withBarangay($barangay)->withBatch($batch);
        } catch(\Exception $e) {
            return redirect()->back();
        }
    }
    public function postStudents(Request $request)
    {
        try {
            if ($request->status == 'Accepted') {
                $application_status = 'Accepted';
                $student_status = 'Continuing';
            } elseif ($request->status == 'Pending') {
                $application_status = 'Pending';
                $student_status = 'Continuing';
            } elseif ($request->status == 'Declined') {
                $application_status = 'Declined';
                $student_status = 'Continuing';
            } elseif ($request->status == 'Continuing') {
                $application_status = 'Accepted';
                $student_status = 'Continuing';
            } elseif ($request->status == 'Graduated') {
                $application_status = 'Accepted';
                $student_status = 'Graduated';
            } elseif ($request->status == 'Forfeit') {
                $application_status = 'Accepted';
                $student_status = 'Forfeit';
            }
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
            ->select('users.*','student_details.*','districts.description as districts_description','barangay.description as barangay_description','schools.abbreviation as schools_abbreviation','courses.abbreviation as courses_abbreviation',DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strUserName"))
            ->where('user_councilor.councilor_id', function($query){
                $query->from('user_councilor')
                ->join('users','user_councilor.user_id','users.id')
                ->join('councilors','user_councilor.councilor_id','councilors.id')
                ->select('councilors.id')
                ->where('users.id',Auth::id())
                ->first();
            })
            ->where('student_details.batch_id',$request->batch)
            ->where('student_details.application_status',$application_status)
            ->where('student_details.student_status',$student_status);
            if (!is_null($request->school)) {
                $application = $application->whereIn('student_details.school_id',$request->school);
            }
            if (!is_null($request->course)) {
                $application = $application->whereIn('student_details.course_id',$request->course);
            }
            if (!is_null($request->barangay)) {
                $application = $application->whereIn('student_details.barangay_id',$request->barangay);
            }
            $application = $application->get();
            $pdf = PDF::loadView('SMS.Coordinator.Queries.CoordinatorQueriesStudentsDocs', compact('application','councilor','today','request'))
            ->setPaper('a4','landscape');
            return $pdf->stream();
        } catch(\Exception $e) {
            return redirect()->back();
        }
    }
    public function grades()
    {
        $budget = Budget::where('user_id',Auth::id())->get();
        return view('SMS.Coordinator.Queries.CoordinatorQueriesGrades')->withBudget($budget);
    }
    public function postGrades(Request $request)
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
            $getUser = $user->map(function ($user) {
                return collect($user->toArray())
                ->only(['user_id'])
                ->all();
            });
            $grade = Grade::join('grade_details','grades.id','grade_details.grade_id')
            ->join('user_budget','user_budget.grade_id','grades.id')
            ->join('student_details','student_details.user_id','user_budget.user_id')
            ->join('users','users.id','user_budget.user_id')
            ->join('schools','student_details.school_id','schools.id')
            ->join('courses','student_details.course_id','courses.id')
            ->select('grade_details.*','grades.*', 'grades.id as grade_id','user_budget.budget_id',DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strUserName"),'schools.abbreviation as schools_abbreviation','courses.abbreviation as courses_abbreviation','users.id as user_id')
            ->whereIn('grades.student_detail_user_id',$getUser)
            ->get();
            foreach ($grade as $key => $grades) {
                $grading = GradingDetail::where('grading_id',$grades->grading_id)
                ->where('grade',$grades->grade)
                ->first();
                if ($grading->status != $request->status) {
                    $grade->forget($key);
                }
            }
            $userbudget = UserBudget::all();
            $pdf = PDF::loadView('SMS.Coordinator.Queries.CoordinatorQueriesGradesDocs', compact('userbudget','request','grade','budget','councilor','today'))
            ->setPaper('a4','landscape');
            return $pdf->stream();
        } catch(\Exception $e) {
            dd($e->getMessage());
            return redirect()->back();
        }
    }
    public function claiming()
    {
        $budget = Budget::where('user_id',Auth::id())->get();
        $type = Budgtype::where('is_active',1)->get();
        return view('SMS.Coordinator.Queries.CoordinatorQueriesClaiming')->withBudget($budget)->withType($type);
    }
    public function postClaiming(Request $request)
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
            ->select('allocations.*','allocation_types.description');
            if (!is_null($request->type)) {
                $allocation = $allocation->whereIn('allocation_types.id',$request->type);
            }
            $allocation = $allocation->get();
            $custom = collect([]);
            foreach ($user as $users) {
                $allocate = UserAllocation::join('receipts','receipts.id','user_allocation.receipt_id')
                ->where('receipts.user_id',$users->user_id)
                ->get();
                foreach ($allocate as $allocates) {
                    $userbudget = UserBudget::join('user_allocation','user_allocation.budget_id','user_budget.budget_id')
                    ->select('user_allocation.*','user_budget.user_id')
                    ->where('user_budget.user_id',$users->user_id)
                    ->where('user_budget.budget_id',$users->budget_id)
                    ->where('user_allocation.allocation_id',$allocates->allocation_id)
                    ->first();
                    if (!is_null($userbudget)) {
                        $custom->push(['strUserName' => $users->strUserName,'user_id' => $users->user_id, 'budget_id' => $users->budget_id, 'allocation_id' => $userbudget->allocation_id]);
                    }
                }
            }
            $pdf = PDF::loadView('SMS.Coordinator.Queries.CoordinatorQueriesClaimingDocs', compact('custom','user','allocation','budget','councilor','today'));
            return $pdf->stream();
        } catch(\Exception $e) {
            dd($e->getMessage());
            return redirect()->back();
        }
    }
}
