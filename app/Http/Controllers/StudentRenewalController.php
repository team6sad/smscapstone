<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Application;
use Auth;
use App\GradingDetail;
use App\Grade;
use App\School;
use App\Course;
use DB;
use App\GradeDetail;
use App\Shift;
use Config;
use Carbon\Carbon;
use App\Utility;
use App\UserBudget;
use App\Credit;
class StudentRenewalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('student');
    }
    public function index()
    {
        $deactivate = Application::find(Auth::id());
        if($deactivate->student_status == 'Graduated' || $deactivate->status == 'Forfeit')
            return view('SMS.Student.StudentDeactivate');
        $grading = GradingDetail::where('grading_id', function($query){
            $query->from('schools')
            ->join('student_details','schools.id','student_details.school_id')
            ->join('gradings','schools.grading_id','gradings.id')
            ->select('schools.grading_id')
            ->where('student_details.user_id',Auth::id())
            ->first();
        })
        ->select('grade')
        ->orderBy('grade','desc')
        ->get();
        $grade = Grade::where('student_detail_user_id',Auth::id())
        ->select('grades.*')
        ->latest('id')
        ->first();
        $application = Application::join('schools','student_details.school_id','schools.id')
        ->join('courses','student_details.course_id','courses.id')
        ->select('student_details.*','schools.description as school_description','courses.description as course_description')
        ->where('student_details.user_id',Auth::id())
        ->first();
        if ($application->is_renewal == 0) {
            $credit = Credit::where('school_id',$application->school_id)->where('course_id',$application->course_id)->first();
            $grade->semester += 1;
            if ($grade->semester > $credit->semester) {
                $grade->semester = 1;
                $grade->year += 1;
            }
        }
        $gradedetail = GradeDetail::where('grade_id', function($query) {
            $query->from('grades')
            ->where('student_detail_user_id', Auth::id())
            ->select('id')
            ->latest('id')
            ->first();
        })
        ->get();
        $shift = Shift::join('schools','shifts.school_id','schools.id')
        ->join('courses','shifts.course_id','courses.id')
        ->where('user_id', Auth::id())
        ->select('courses.description as course_description','schools.description as school_description')
        ->latest('shifts.id')
        ->first();
        if ($shift == null) {
            $shift = (object)['school_description' => 'N/A', 'course_description' => 'N/A'];
        }
        $school = School::where('is_active',1)->get();
        $course = Course::where('is_active',1)->get();
        $utility = Utility::where('user_id', function($query) {
            $query->from('users')
            ->join('user_councilor','users.id','user_councilor.user_id')
            ->select('users.id')
            ->where('user_councilor.councilor_id', function($subquery) {
                $subquery->from('user_councilor')
                ->select('councilor_id')
                ->where('user_id', Auth::id())
                ->first();
            })
            ->where('users.type','Coordinator')
            ->first();
        })
        ->first();
        $userbudget = UserBudget::where('user_id',Auth::id())
        ->where('budget_id', function($query) {
            $query->from('budgets')
            ->where('councilor_id', function($subquery) {
                $subquery->from('user_councilor')
                ->where('user_id',Auth::id())
                ->select('councilor_id')
                ->first();
            })
            ->latest('id')
            ->select('id')
            ->first();
        })->count();
        $number = new NumberToWord;
        $grade->year = $number->number($grade->year);
        $grade->semester = $number->number($grade->semester);
        return view('SMS.Student.StudentRenewal')->withApplication($application)->withGrading($grading)->withGrade($grade)->withSchool($school)->withCourse($course)->withUtility($utility)->withGradedetail($gradedetail)->withShift($shift)->withUserbudget($userbudget);
    }
    public function store(Request $request)
    {
        //PDF Upload
        $pdf = $request->file('strApplGrades');
        $pdfname = md5(Auth::user()->email. time()).'.'.$pdf->getClientOriginalExtension();
        $school = School::join('student_details','schools.id','student_details.school_id')
        ->join('gradings','schools.grading_id','gradings.id')
        ->select('schools.grading_id')
        ->where('student_details.user_id',Auth::id())
        ->first();
        $grade = Grade::where('student_detail_user_id',Auth::id())
        ->select('grades.*')
        ->latest('id')
        ->first();
        $application = Application::find(Auth::id());
        $credit = Credit::where('school_id',$application->school_id)->where('course_id',$application->course_id)->first();
        $grade->semester += 1;
        if ($grade->semester > $credit->semester) {
            $grade->semester = 1;
            $grade->year += 1;
        }
        DB::beginTransaction();
        try {
            $application = Application::find(Auth::id());
            $application->is_renewal = 1;
            $application->save();
            $grades = new Grade;
            $grades->student_detail_user_id = Auth::id();
            $grades->grading_id = $school->grading_id;
            $grades->year = $grade->year;
            $grades->semester = $grade->semester;
            $grades->pdf = $pdfname;
            $grades->save();
            //Manual Input of Grades
            for ($i=0; $i < count($request->subject_description); $i++) {
                $detail = new GradeDetail;
                $detail->grade_id=$grades->id;
                $detail->description=$request->subject_description[$i];
                $detail->units=$request->units[$i];
                $detail->grade=$request->subject_grade[$i];
                $detail->save();
            }
            if ($request->rad == 'yes') {
                $application = Application::find(Auth::id());
                $shift = new Shift;
                $shift->user_id = Auth::id();
                $shift->school_id = $application->school_id;
                $shift->course_id = $application->course_id;
                $shift->shift_date = Carbon::now(Config::get('app.timezone'));
                $shift->save();
                $application->school_id = $request->school_transfer;
                $application->course_id = $request->course_transfer;
                $application->save();
            }
            $pdf->move(base_path().'/public/docs/', $pdfname);
            DB::commit();
            return redirect(route('studentrenewal.index'));
        } catch(\Exception $e) {
            DB::rollBack();
            return dd($e->getMessage());
        } 
    }
}
