<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Utility;
use Auth;
use App\Application;
use App\Budget;
class CoordinatorIndexController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('coordinator');
	}
	public function index()
	{
		$utility = Utility::find(Auth::id());
		$latest = Budget::where('user_id',Auth::id())->latest('id')->first();
		$applicants = Application::join('users','users.id','student_details.user_id')
		->join('user_councilor','users.id','user_councilor.user_id')
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
		->where('users.is_active',0)
		->where('users.type','Student')
		->count();
		$renewal = Application::join('users','student_details.user_id','users.id')
		->join('user_councilor','users.id','user_councilor.user_id')
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
		->where('student_details.student_status','Continuing')
		->where('student_details.is_renewal',1)
		->count();
		$scholar = Application::join('users','student_details.user_id','users.id')
		->join('user_councilor','users.id','user_councilor.user_id')
		->where('users.type','Student')
		->where('user_councilor.councilor_id', function($query){
			$query->from('user_councilor')
			->join('users','user_councilor.user_id','users.id')
			->join('councilors','user_councilor.councilor_id','councilors.id')
			->select('councilors.id')
			->where('user_councilor.user_id',Auth::id())
			->first();
		})
		->where('student_details.student_status','Continuing')
		->where('student_details.application_status','Accepted')
		->count();
		return view('SMS.Coordinator.CoordinatorIndex')->withUtility($utility)->withApplicants($applicants)->withRenewal($renewal)->withScholar($scholar)->withLatest($latest);
	}
}
