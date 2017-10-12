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
        ->whereBetween('date_held',[$date_from,$date_to]);
        if (!is_null($request->status)) {
            $event = $event->whereIn('status',$request->status);
        }
        $event = $event->get();
        $pdf = PDF::loadView('SMS.Coordinator.Queries.CoordinatorQueriesEventsDocs', compact('event','councilor','today'));
        return $pdf->stream();
    }
    public function students()
    {
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
    }
    public function postStudents(Request $request)
    {
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
    }
}
