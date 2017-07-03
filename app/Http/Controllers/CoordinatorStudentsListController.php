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
use Carbon\Carbon;
class CoordinatorStudentsListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function index()
    {
        $district = District::where('is_active',1)->get();
        $councilor = Councilor::where('is_active',1)->get();
        $barangay = Barangay::where('is_active',1)->get();
        $school = School::where('is_active',1)->get();
        $course = Course::where('is_active',1)->get();
        $batch = Batch::where('is_active',1)->get();
        return view('SMS.Coordinator.Scholar.CoordinatorStudentsList')->withDistrict($district)->withCouncilor($councilor)->withBarangay($barangay)->withSchool($school)->withCourse($course)->withBatch($batch);
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
        ->where('student_details.application_status','Accepted');
        $datatables = Datatables::of($application)
        ->editColumn('checkbox', function ($data) {
            $checked = '';
            if($data->is_active==1){
                $checked = 'checked';
            }
            return "<input type='checkbox' id='isActive' name='isActive' value='$data->user_id' data-toggle='toggle' data-style='android' data-onstyle='success' data-offstyle='danger' data-on=\"<i class='fa fa-check-circle'></i> Active\" data-off=\"<i class='fa fa-times-circle'></i> Inactive\" $checked data-size='mini'><script>
            $('[data-toggle=\'toggle\']').bootstrapToggle('destroy');   
            $('[data-toggle=\'toggle\']').bootstrapToggle();</script>";
        })
        ->editColumn('student_status', function ($data) {
            $selected1 = '';
            $selected2 = '';
            $selected3 = '';
            if ($data->student_status == 'Continuing') {
                $color = 'warning';
                $selected1 = 'selected';
            }elseif ($data->student_status == 'Graduated') {
                $color = 'success';
                $selected2 = 'selected';
            }else {
                $color = 'danger';
                $selected3 = 'selected';
            }
            return "<select id='student_status' selectedbox = '$data->id' class='btn-xs btn-$color' name='student_status'>
            <option value='Continuing' class='btn-warning' $selected1> Continuing</option>
            <option value='Graduated' class='btn-success' $selected2><i class='fa fa-edit'></i> Graduated</option>
            <option value='Forfeit' class='btn-danger' $selected3><i class='fa fa-edit'></i> Forfeit</option></select>";
        })
        ->addColumn('action', function ($data) {
            return "<button class='btn btn-info btn-xs open-modal'><i class='fa fa-eye'></i> View</button> <button class='btn btn-warning btn-xs'><i class='fa fa-edit'></i> Edit</button>";
        })
        ->editColumn('strStudName', function ($data) {
            $images = url('images/'.$data->picture);
            return "<table><tr><td><div class='col-md-2'><img src='$images' class='img-circle' alt='User Image' height='40'></div></td><td>$data->last_name, $data->first_name $data->middle_name</td></tr></table>";
        })
        ->editColumn('application_date', function ($data) {
            return $data->application_date ? with(new Carbon($data->application_date))->format('M d, Y') : '';
        })
        ->setRowId(function ($data) {
            return $data = 'id'.$data->user_id;
        })
        ->rawColumns(['strStudName','student_status','checkbox','action']);
        if ($strUserFirstName = $request->get('strUserFirstName')) {
            $datatables->where('users.first_name', 'like', '%'.$strUserFirstName.'%');
        }
        if ($strUserMiddleName = $request->get('strUserMiddleName')) {
            $datatables->where('users.middle_name', 'like', '%'.$strUserMiddleName.'%');
        }
        if ($strUserLastName = $request->get('strUserLastName')) {
            $datatables->where('users.last_name', 'like', '%'.$strUserLastName.'%');
        }
        if ($intDistID = $request->get('intDistID')) {
            $datatables->where('student_details.district_id', 'like', '%'.$intDistID.'%');
        }
        if ($intBaraID = $request->get('intBaraID')) {
            $datatables->where('student_details.barangay_id', 'like', '%'.$intBaraID.'%');
        }
        if ($intBatcID = $request->get('intBatcID')) {
            $datatables->where('student_details.batch_id', 'like', '%'.$intBatcID.'%');
        }
        if ($strPersStreet = $request->get('strPersStreet')) {
            $datatables->where('student_details.street', 'like', '%'.$strPersStreet.'%');
        }
        if ($strPersReligion = $request->get('strPersReligion')) {
            $datatables->where('student_details.religion', 'like', '%'.$strPersReligion.'%');
        }
        if ($keyword = $request->get('search')['value']) {
            $datatables->filterColumn('strStudName', 'whereRaw', "CONCAT(users.last_name,', ',users.first_name,' ',IFNULL(users.middle_name,'')) like ? ", ["%$keyword%"]);
        }
        return $datatables->make(true);
    }
    public function checkbox($id)
    {
        try {
            $user = User::findorfail($id);
            if ($user->is_active) {
                $user->is_active=0;
            }
            else {
                $user->is_active=1;
            }
            $user->save();
        } catch(\Exception $e) {
            return "Deleted";
        } 
    }
    public function update(Request $request, $id)
    {
        try {
            $application = Application::findorfail($id);
            $application->student_status = $request->student_status;
            $application->save();
            return Response::json($application);
        } catch(\Exception $e) {
            return "Deleted";
        } 
    }
}
