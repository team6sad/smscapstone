<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Credit;
use App\School;
use App\Course;
use App\Setting;
use Response;
use Datatables;
use Validator;
class AdminMCreditController extends Controller
{
	public function data()
	{
		$credit = Credit::join('schools','school_course.school_id','schools.id')
		->join('courses','school_course.course_id','courses.id')
		->select(['schools.description as schools_description','courses.description as courses_description','school_course.*']);
		return Datatables::of($credit)
		->addColumn('action', function ($data) {
			return "<button class='btn btn-warning btn-xs btn-detail open-modal' value='$data->id'><i class='fa fa-edit'></i> Edit</button> <button class='btn btn-danger btn-xs btn-delete' value='$data->id'><i class='fa fa-trash-o'></i> Delete</button>";
		})
		->setRowId(function ($data) {
			return $data = 'id'.$data->id;
		})
		->rawColumns(['action'])
		->make(true);
	}
	public function index()
	{
		$setting = Setting::first();
		$school = School::where('is_active',1)->pluck('description','id');
		$course = Course::where('is_active',1)->pluck('description','id');
		return view('SMS.Admin.Maintenance.AdminMCredit')->withCourse($course)->withSchool($school)->withSetting($setting);
	}
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), Credit::$storeRule);
		if ($validator->fails()) {
			return Response::json($validator->errors()->first(), 422);
		}
		try {
			$credit = new Credit;
			$credit->school_id = $request->school_id;
			$credit->course_id = $request->course_id;
			$credit->year = $request->year;
			$credit->semester = $request->semester;
			$credit->save();
			return Response::json($credit);
		} catch(\Exception $e) {
			return $e->getMessage();
		}
	}
	public function edit($id)
	{
		try {
			$credit = Credit::join('schools','school_course.school_id','schools.id')
			->join('courses','school_course.course_id','courses.id')
			->select('schools.description as schools_description','courses.description as courses_description','school_course.*')
			->where('school_course.id',$id)
			->firstorfail();
			return Response::json($credit);
		} catch(\Exception $e) {
			return "Deleted";
		}
	}
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), Credit::updateRule($id));
		if ($validator->fails()) {
			return Response::json($validator->errors()->first(), 422);
		}
		try {
			try {
				$credit = Credit::findorfail($id);
				$credit->school_id = $request->school_id;
				$credit->course_id = $request->course_id;
				$credit->year = $request->year;
				$credit->semester = $request->semester;
				$credit->save();
				return Response::json($credit);
			}
			catch(\Exception $e) {
				return $e->getMessage();
			}
		} catch(\Exception $e) {
			return Response::json("The record is invalid or deleted.", 422);
		}
	}
	public function destroy($id)
	{
		try {
			$credit = Credit::findorfail($id);
			try {
				$credit->delete();
				return Response::json($credit);
			} catch(\Exception $e) {
				if($e->getCode()==1451)
					return Response::json(['true',$credit]);
				else
					return Response::json(['true',$credit,$e->getMessage()]);
			}
		} catch(\Exception $e) {
			return "Deleted";
		}
	}
}
