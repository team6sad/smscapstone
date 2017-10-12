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
            $allgrades->semester -= 1;
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
    }
}
