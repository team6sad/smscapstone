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
        $date_from = Carbon::createFromFormat('Y-m-d', $request->date_from);
        $date_to = Carbon::createFromFormat('Y-m-d', $request->date_to);
        $councilor = Councilor::join('user_councilor','user_councilor.councilor_id','councilors.id')
        ->join('districts','districts.id','councilors.district_id')
        ->where('user_councilor.user_id',Auth::id())
        ->first();
        $today = Carbon::today();
        $event = Event::where('user_id',Auth::id())
        ->whereBetween('date_held',[$date_from,$date_to])
        ->whereIn('status',$request->status)->get();
        $pdf = PDF::loadView('SMS.Coordinator.Queries.CoordinatorQueriesEventsDocs', compact('event','councilor','today'));
        return $pdf->stream();
    }
    public function students()
    {
        $school = School::where('is_active',1)->get();
        $course = Course::where('is_active',1)->get();
        $batch = Batch::where('is_active',1)->get();
        return view('SMS.Coordinator.Queries.CoordinatorQueriesStudents')->withSchool($school)->withCourse($course)->withBatch($batch);
    }
    public function postStudents(Request $request)
    {
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
        ->get();
        $pdf = PDF::loadView('SMS.Coordinator.Queries.CoordinatorQueriesStudentsDocs', compact('application','councilor','today'));
        return $pdf->stream();
    }
}
