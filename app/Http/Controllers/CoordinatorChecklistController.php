<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Datatables;
use App\School;
use App\Course;
use App\Budgtype;
use App\UserSchool;
use App\UserCourse;
use App\UserAllocationType;
use Auth;
use Response;
class CoordinatorChecklistController extends Controller
{
	public function index()
	{
		return view('SMS.Coordinator.Services.CoordinatorChecklist');
	}
	public function dataSchool()
	{
		$school = School::where('is_active',1);
		return Datatables::of($school)
		->addColumn('is_active', function ($data) {
			$school = UserSchool::where('school_id',$data->id)
			->where('user_id',Auth::id())
			->first();
			$checked = '';
			if($school != null){
				$checked = 'checked';
			}
			return "<input type='checkbox' id='isActive' name='isActive' value='$data->id' data-toggle='toggle' data-style='android' data-onstyle='success' data-offstyle='danger' data-on=\"<i class='fa fa-check-circle'></i> Active\" data-off=\"<i class='fa fa-times-circle'></i> Inactive\" $checked data-size='mini'><script>
			$('[data-toggle=\'toggle\']').bootstrapToggle('destroy');   
			$('[data-toggle=\'toggle\']').bootstrapToggle();</script>";
		})
		->setRowId(function ($data) {
			return $data = 'id'.$data->id;
		})
		->rawColumns(['is_active','action'])
		->make(true);
	}
	public function checkboxSchool(Request $request, $id)
	{
		try {
			if ($request->is_active) {
				$school = new UserSchool;
				$school->school_id = $id;
				$school->user_id = Auth::id();
				$school->save();
			} else {
				$school = UserSchool::where('user_id',Auth::id())->where('school_id',$id)->firstorfail();
				$school->delete();
			}
			return Response::json(200);
		} catch(\Exception $e) {
			return Response::json('Already Add or Remove', 500);
		}
	}
	public function dataCourse()
	{
		$course = Course::where('is_active',1);
		return Datatables::of($course)
		->addColumn('is_active', function ($data) {
			$course = UserCourse::where('course_id',$data->id)
			->where('user_id',Auth::id())
			->first();
			$checked = '';
			if($course != null){
				$checked = 'checked';
			}
			return "<input type='checkbox' id='isActive' name='isActive' value='$data->id' data-toggle='toggle' data-style='android' data-onstyle='success' data-offstyle='danger' data-on=\"<i class='fa fa-check-circle'></i> Active\" data-off=\"<i class='fa fa-times-circle'></i> Inactive\" $checked data-size='mini'><script>
			$('[data-toggle=\'toggle\']').bootstrapToggle('destroy');   
			$('[data-toggle=\'toggle\']').bootstrapToggle();</script>";
		})
		->setRowId(function ($data) {
			return $data = 'id'.$data->id;
		})
		->rawColumns(['is_active','action'])
		->make(true);
	}
	public function checkboxCourse(Request $request, $id)
	{
		try {
			if ($request->is_active) {
				$course = new UserCourse;
				$course->course_id = $id;
				$course->user_id = Auth::id();
				$course->save();
			} else {
				$course = UserCourse::where('user_id',Auth::id())->where('course_id',$id)->firstorfail();
				$course->delete();
			}
			return Response::json(200);
		} catch(\Exception $e) {
			return Response::json('Already Add or Remove', 500);
		}
	}
	public function dataClaiming()
	{
		$claiming = Budgtype::where('is_active',1);
		return Datatables::of($claiming)
		->addColumn('is_active', function ($data) {
			$type = UserAllocationType::where('allocation_type_id',$data->id)
			->where('user_id',Auth::id())
			->first();
			$checked = '';
			if($type != null){
				$checked = 'checked';
			}
			return "<input type='checkbox' id='isActive' name='isActive' value='$data->id' data-toggle='toggle' data-style='android' data-onstyle='success' data-offstyle='danger' data-on=\"<i class='fa fa-check-circle'></i> Active\" data-off=\"<i class='fa fa-times-circle'></i> Inactive\" $checked data-size='mini'><script>
			$('[data-toggle=\'toggle\']').bootstrapToggle('destroy');   
			$('[data-toggle=\'toggle\']').bootstrapToggle();</script>";
		})
		->setRowId(function ($data) {
			return $data = 'id'.$data->id;
		})
		->rawColumns(['is_active','action'])
		->make(true);
	}
	public function checkboxClaiming(Request $request, $id)
	{
		try {
			if ($request->is_active) {
				$type = new UserAllocationType;
				$type->allocation_type_id = $id;
				$type->user_id = Auth::id();
				$type->save();
			} else {
				$type = UserAllocationType::where('user_id',Auth::id())->where('allocation_type_id',$id)->firstorfail();
				$type->delete();
			}
			return Response::json(200);
		} catch(\Exception $e) {
			return Response::json('Already Add or Remove', 500);
		}
	}
}
