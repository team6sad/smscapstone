<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Studentsteps;
use App\Application;
use DB;
use Auth;
use Datatables;
use Carbon\Carbon;
use App\Utility;
use Session;
use App\Grade;
use App\GradingDetail;
use App\Sibling;
use App\User;
class CoordinatorApplicantsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function data(Request $request)
    {
        $is_active = 0;
        if ($request->status == 'Accepted') {
            $is_active = 1;
        }
        $users = Application::join('users','users.id','student_details.user_id')
        ->join('user_councilor','users.id','user_councilor.user_id')
        ->join('schools','student_details.school_id','schools.id')
        ->join('courses','student_details.course_id','courses.id')
        ->select('users.*','student_details.*','schools.description','courses.description as courses_description')
        ->where('student_details.application_status',$request->status)
        ->where('user_councilor.councilor_id', function($query){
            $query->from('user_councilor')
            ->join('users','user_councilor.user_id','users.id')
            ->join('councilors','user_councilor.councilor_id','councilors.id')
            ->select('councilors.id')
            ->where('user_councilor.user_id',Auth::id())
            ->first();
        })
        ->where('student_details.batch_id', function($query){
            $query->from('batches')
            ->select('id')
            ->latest('id')
            ->first();
        })
        ->where('users.is_active',$is_active)
        ->where('users.type','Student');
        return Datatables::of($users)
        ->editColumn('strUserName', function ($data) {
            $images = url('images/'.$data->picture);
            return "<table><tr><td><div class='col-md-2'><img src='$images' class='img-circle' alt='data Image' height='40'></div></td><td>$data->last_name, $data->first_name $data->middle_name</td></tr></table>";
        })
        ->addColumn('action', function ($data) {
            return "<a href=".route('details.show',$data->id)."><button class='btn btn-info btn-xs btn-view' value='$data->id'><i class='fa fa-eye'></i> View</button></a>";
        })
        ->editColumn('application_date', function ($data) {
            return $data->application_date ? with(new Carbon($data->application_date))->format('M d, Y - h:i A') : '';
        })
        ->setRowId(function ($data) {
            return $data = 'id'.$data->id;
        })
        ->rawColumns(['action','strUserName'])
        ->make(true);
    }
    public function index()
    {
        $this->criteria();
        $utility = Utility::where('user_id',Auth::id())->first();
        return view('SMS.Coordinator.Scholar.CoordinatorApplicants')->withUtility($utility);
    }
    public function postCriteria(Request $request)
    {
        $utility = Utility::find(Auth::id());
        $utility->income_cap = $request->income_cap;
        if (is_null($request->passing_grades))
            $utility->passing_grades = 0;
        else 
            $utility->passing_grades = 1;
        if (is_null($request->no_siblings))
            $utility->no_siblings = 0;
        else
            $utility->no_siblings = 1;
        $utility->save();
        Session::flash('success','Data Stored');
        return redirect()->back();
    }
    private function criteria()
    {
        $user = Application::join('users','users.id','student_details.user_id')
        ->join('user_councilor','users.id','user_councilor.user_id')
        ->select('users.*')
        ->where('student_details.application_status','Pending')
        ->where('user_councilor.councilor_id', function($query){
            $query->from('user_councilor')
            ->join('users','user_councilor.user_id','users.id')
            ->join('councilors','user_councilor.councilor_id','councilors.id')
            ->select('councilors.id')
            ->where('user_councilor.user_id',Auth::id())
            ->first();
        })
        ->where('student_details.batch_id', function($query){
            $query->from('batches')
            ->select('id')
            ->latest('id')
            ->first();
        })
        ->where('users.type','Student')
        ->get();
        $utility = Utility::find(Auth::id());
        foreach ($user as $users) {
            if ($utility->passing_grades) {
                $grades = Grade::join('grade_details','grades.id','grade_details.grade_id')
                ->select('grade_details.*','grades.*', 'grades.id as grade_id')
                ->where('grades.student_detail_user_id',$users->id)
                ->count();
                if ($grades!=0) {
                    $grades = Grade::join('grade_details','grades.id','grade_details.grade_id')
                    ->select('grade_details.*','grades.*', 'grades.id as grade_id')
                    ->where('grades.student_detail_user_id',$users->id)
                    ->get();
                    foreach ($grades as $details) {
                        $grading = GradingDetail::where('grading_id',$details->grading_id)
                        ->where('grade',$details->grade)
                        ->first();
                        if ($grading->is_passed == 0) {
                            $this->declined('Failed Grades', $users);
                            break;
                        }
                    }
                }
            }
            if ($utility->no_siblings) {
                $sibling = Sibling::where('student_detail_user_id',$users->id)->count();
                if ($sibling > 0) {
                    $this->declined('Have a sibling affiliated', $users);
                }
                $siblings = User::where('middle_name',$users->middle_name)->where('last_name',$users->last_name)->count();
                if ($siblings > 1) {
                    $this->declined('Have a sibling affiliated', $users);
                }
            }
        }
    }
    private function declined($remarks, $users)
    {
        $application = Application::find($users->id);
        $application->application_status = 'Declined';
        $application->remarks = $remarks;
        $application->save();
    }
}
