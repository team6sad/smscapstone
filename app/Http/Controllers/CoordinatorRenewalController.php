<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Application;
use DB;
use Auth;
use Datatables;
use App\Grade;
use App\GradingDetail;
use App\UserBudget;
use App\Budget;
use Response;
class CoordinatorRenewalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function data()
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
        ->where('student_details.is_renewal',1);
        return Datatables::of($application)
        ->addColumn('action', function ($data) {
            $pdf = Grade::where('student_detail_user_id',$data->user_id)->latest('id')->select('pdf')->first();
            return "<a href=".asset('docs/'.$pdf->pdf)." target='_blank'><button class='btn btn-info btn-xs' value='$data->id'><i class='fa fa-eye'></i> View</button></a> <button class='btn btn-success btn-accept btn-xs' value='$data->id'><i class='fa fa-check'></i> Accept</button> <button class='btn btn-danger btn-decline btn-xs' value='$data->id'><i class='fa fa-remove'></i> Decline</button>";
        })
        ->addColumn('failed', function ($data) {
            $ctr = 0;
            $grade = Grade::join('grade_details','grades.id','grade_details.grade_id')
            ->where('grade_details.grade_id', function($query) use($data){
                $query->from('grades')
                ->select('id')
                ->where('student_detail_user_id', $data->id)
                ->latest('id')
                ->first();
            })
            ->select('grade_details.grade','grades.grading_id')
            ->get();
            $grading = GradingDetail::where('grading_id',$grade[0]->grading_id)
            ->select('grading_details.*')
            ->get();
            foreach ($grade as $grades) {
                foreach ($grading as $gradings) {
                    if ($grades->grade==$gradings->grade) {
                        if (!$gradings->is_passed) {
                            $ctr++;
                        }
                    }
                }
            }
            return $ctr;
        })
        ->editColumn('strStudName', function ($data) {
            $images = url('images/'.$data->picture);
            return "<table><tr><td><div class='col-md-2'><img src='$images' class='img-circle' alt='data Image' height='40'></div></td><td>$data->last_name, $data->first_name $data->middle_name</td></tr></table>";
        })
        ->setRowId(function ($data) {
            return $data = 'id'.$data->id;
        })
        ->rawColumns(['strStudName','action'])
        ->make(true);
    }
    public function index()
    {
        return view('SMS.Coordinator.Scholar.CoordinatorRenewal');
    }
    public function accept($id)
    {
        DB::beginTransaction();
        try {
            $budget = Budget::where('user_id',Auth::id())
            ->latest('id')->first();
            $application = Application::find($id);
            $application->is_renewal = 0;
            $application->save();
            $userbudget = new UserBudget;
            $userbudget->budget_id = $budget->id;
            $userbudget->user_id = $id;
            $userbudget->save();
            DB::commit();
            return Response::json($userbudget);
        } catch(\Exception $e) {
            DB::rollBack();
            return dd($e->getMessage(),500);
        }  
    }
    public function decline($id)
    {
        DB::beginTransaction();
        try {
            $application = Application::find($id);
            $application->is_renewal = 0;
            $application->student_status = 'Forfeit';
            $application->save();
            DB::commit();
            return Response::json($application);
        } catch(\Exception $e) {
            DB::rollBack();
            return dd($e->getMessage(),500);
        }  
    }
}
