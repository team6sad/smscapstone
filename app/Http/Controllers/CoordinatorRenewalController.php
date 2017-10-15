<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Application;
use DB;
use Auth;
use Datatables;
use App\Grade;
use App\GradeDetail;
use App\GradingDetail;
use App\UserBudget;
use App\Budget;
use Response;
use App\Utility;
use Session;
use Carbon\Carbon;
use Config;
use App\Message;
use App\Receiver;
class CoordinatorRenewalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function data(Request $request)
    {
        if ($request->status == 'Accepted') {
            $is_renewal = 0;
            $student_status = 'Continuing';
        } elseif ($request->status == 'Pending') {
            $is_renewal = 1;
            $student_status = 'Continuing';
        } else {
            $is_renewal = 0;
            $student_status = 'Forfeit';
        }
        $application = Application::join('users','student_details.user_id','users.id')
        ->join('user_councilor','users.id','user_councilor.user_id')
        ->select([DB::raw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) as strStudName"),'users.*','student_details.*'])
        ->where('users.type','Student');
        if ($request->status == 'Accepted') {
            $application = $application->join('user_budget','user_budget.user_id','users.id')
            ->where('user_budget.budget_id', function($query) {
                $query->from('budgets')
                ->where('user_id',Auth::id())
                ->latest('id')
                ->select('id')
                ->first();
            });
        }
        $application = $application->where('user_councilor.councilor_id', function($query) {
            $query->from('user_councilor')
            ->join('users','user_councilor.user_id','users.id')
            ->join('councilors','user_councilor.councilor_id','councilors.id')
            ->select('councilors.id')
            ->where('user_councilor.user_id',Auth::id())
            ->first();
        })
        ->where('student_details.application_status','Accepted')
        ->where('student_details.student_status',$student_status)
        ->where('student_details.is_renewal',$is_renewal);
        return Datatables::of($application)
        ->filterColumn('strStudName', function($query, $keyword) {
            $query->whereRaw("CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) like ?", ["%{$keyword}%"]);
        })
        ->addColumn('action', function ($data) use ($request){
            $pdf = Grade::where('student_detail_user_id',$data->user_id)->latest('id')->select('pdf')->first();
            if ($request->status == 'Pending') {
                return "<a href=".asset('docs/'.$pdf->pdf)." target='_blank'><button class='btn btn-info btn-xs' value='$data->id'><i class='fa fa-eye'></i> View</button></a> <button class='btn btn-success btn-accept btn-xs' value='$data->id'><i class='fa fa-check'></i> Accept</button> <button class='btn btn-danger btn-decline btn-xs' value='$data->id'><i class='fa fa-remove'></i> Decline</button>";
            } else {
                return "<a href=".asset('docs/'.$pdf->pdf)." target='_blank'><button class='btn btn-info btn-xs' value='$data->id'><i class='fa fa-eye'></i> View</button></a>";
            }
        })
        ->addColumn('withdraw', function ($data) {
            $ctr = 0;
            $grade = Grade::where('student_detail_user_id', $data->id)
            ->latest('id')
            ->first();
            $grading = GradingDetail::where('grading_id',$grade->grading_id)
            ->get();
            $detail = GradeDetail::where('grade_id',$grade->id)
            ->get();
            foreach ($detail as $details) {
                foreach ($grading as $gradings) {
                    if ($details->grade==$gradings->grade) {
                        if ($gradings->status == 'W') {
                            $ctr++;
                        }
                    }
                }
            }
            return $ctr;
        })
        ->addColumn('drop', function ($data) {
            $ctr = 0;
            $grade = Grade::where('student_detail_user_id', $data->id)
            ->latest('id')
            ->first();
            $grading = GradingDetail::where('grading_id',$grade->grading_id)
            ->get();
            $detail = GradeDetail::where('grade_id',$grade->id)
            ->get();
            foreach ($detail as $details) {
                foreach ($grading as $gradings) {
                    if ($details->grade==$gradings->grade) {
                        if ($gradings->status == 'D') {
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
        $this->criteria();
        $utility = Utility::where('user_id',Auth::id())->first();
        return view('SMS.Coordinator.Scholar.CoordinatorRenewal')->withUtility($utility);
    }
    public function accept($id)
    {
        DB::beginTransaction();
        try {
            $budget = Budget::where('user_id',Auth::id())
            ->latest('id')->first();
            if ($budget != null) {
                $userbudget = UserBudget::where('budget_id',$budget->id)->count();
                if (($budget->slot_count - $userbudget) == 0) {
                    return Response::json('No available slot',500);
                }
                $this->messageAccepted($id, $budget);
            } else {
                return Response::json('No available slot',500);
            }
            DB::commit();
            return Response::json($userbudget);
        } catch(\Exception $e) {
            DB::rollBack();
            return dd($e->getMessage(),500);
        }  
    }
    public function decline(Request $request,$id)
    {
        DB::beginTransaction();
        try {
            $this->messageDecline($id,$request->title,$request->description);
            DB::commit();
            return Response::json('',200);
        } catch(\Exception $e) {
            DB::rollBack();
            return dd($e->getMessage(),500);
        }  
    }
    public function postCriteria(Request $request)
    {
        $utility = Utility::find(Auth::id());
        if (is_null($request->passing_grades))
            $utility->passing_grades = 0;
        else 
            $utility->passing_grades = 1;
        if (is_null($request->renewal_auto_accept))
            $utility->renewal_auto_accept = 0;
        else
            $utility->renewal_auto_accept = 1;
        $utility->save();
        Session::flash('success','Data Stored');
        return redirect()->back();
    }
    private function criteria()
    {
        $failed = 0;
        $passed = 0;
        $user = Application::join('users','student_details.user_id','users.id')
        ->join('user_councilor','users.id','user_councilor.user_id')
        ->select('users.*')
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
        ->where('student_details.is_renewal',1)
        ->get();
        $utility = Utility::find(Auth::id());
        foreach ($user as $users) {
            if ($utility->passing_grades) {
                $grades = Grade::join('grade_details','grades.id','grade_details.grade_id')
                ->select('grade_details.*','grades.*', 'grades.id as grade_id')
                ->where('grades.student_detail_user_id',$users->id)
                ->where('grades.id', function($query) use($users){
                    $query->from('grades')
                    ->where('student_detail_user_id',$users->id)
                    ->latest('id')
                    ->select('id')
                    ->first();
                })
                ->get();
                foreach ($grades as $details) {
                    $grading = GradingDetail::where('grading_id',$details->grading_id)
                    ->where('grade',$details->grade)
                    ->first();
                    if ($grading->status == 'F') {
                        DB::beginTransaction();
                        try {
                            $this->messageDecline($users->id,'Failed Grades','The system detected you have a failed grade');
                            $failed++;
                            DB::commit();
                        } catch(\Exception $e) {
                            DB::rollBack();
                        }
                        break;
                    }
                }
            }
            if ($utility->renewal_auto_accept) {
                $pass = true;
                $grades = Grade::join('grade_details','grades.id','grade_details.grade_id')
                ->select('grade_details.*','grades.*', 'grades.id as grade_id')
                ->where('grades.student_detail_user_id',$users->id)
                ->where('grades.id', function($query) use($users){
                    $query->from('grades')
                    ->where('student_detail_user_id',$users->id)
                    ->latest('id')
                    ->select('id')
                    ->first();
                })
                ->get();
                foreach ($grades as $details) {
                    $grading = GradingDetail::where('grading_id',$details->grading_id)
                    ->where('grade',$details->grade)
                    ->first();
                    if ($grading->status == 'F' || $grading->status == 'D' || $grading->status == 'W') {
                        $pass = false;
                        break;
                    }
                } if ($pass) {
                    $budget = Budget::where('user_id',Auth::id())
                    ->latest('id')->first();
                    if ($budget != null) {
                        $userbudget = UserBudget::where('budget_id',$budget->id)->count();
                        if (($budget->slot_count - $userbudget) == 0) {
                            $utility->renewal_auto_accept = 0;
                            $utility->save();
                        }
                        DB::beginTransaction();
                        try {
                            $this->messageAccepted($users->id, $budget);
                            $passed++;
                            DB::commit();
                        } catch(\Exception $e) {
                            DB::rollBack();
                        }  
                    } else {
                        $utility->renewal_auto_accept = 0;
                        $utility->save();
                    }
                }
            }
        } if ($utility->renewal_auto_accept || $utility->passing_grades) {
            if ($passed > 0 || $failed > 0) {
                Session::flash('success','Accepted '.$passed.' & Declined '.$failed.' Student/s');
            }
        }
    }
    private function messageDecline($id, $remarks, $description)
    {
        $application = Application::find($id);
        $application->is_renewal = 0;
        $application->student_status = 'Forfeit';
        $application->remarks = $remarks;
        $application->save();
        $message = new Message;
        $message->user_id = Auth::id();
        $message->title = $remarks;
        $message->description = $description;
        $message->date_created = Carbon::now(Config::get('app.timezone'));
        $message->save();
        $receiver = new Receiver;
        $receiver->message_id = $message->id;
        $receiver->user_id = $id;
        $receiver->save();
    }
    private function messageAccepted($id, $budget)
    {
        $application = Application::find($id);
        $application->is_renewal = 0;
        $application->save();
        $getGrade = Grade::where('student_detail_user_id',$id)->latest('id')->first();
        $userbudget = new UserBudget;
        $userbudget->budget_id = $budget->id;
        $userbudget->grade_id = $getGrade->id;
        $userbudget->user_id = $id;
        $userbudget->save();
        $message = new Message;
        $message->user_id = Auth::id();
        $message->title = "Accepted in Renewal Phase";
        $message->description = "Passed Requirements";
        $message->date_created = Carbon::now(Config::get('app.timezone'));
        $message->save();
        $receiver = new Receiver;
        $receiver->message_id = $message->id;
        $receiver->user_id = $id;
        $receiver->save();
    }
}