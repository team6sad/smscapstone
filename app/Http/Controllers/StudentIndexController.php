<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\UserAllocation;
use App\Requirement;
use App\Studentsteps;
use App\Grade;
use Auth;
use App\Application;
class StudentIndexController extends Controller
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
		$allocation = UserAllocation::join('allocations','allocations.id','user_allocation.allocation_id')
		->join('allocation_types','allocations.allocation_type_id','allocation_types.id')
		->select('allocation_types.description','allocations.amount','user_allocation.id','user_allocation.date_claimed')
		->where('allocations.budget_id', function($query){
			$query->from('budgets')
			->select('id')
			->latest('id')
			->first();
		})
		->where('user_allocation.grade_id', function($query){
			$query->from('grades')
			->where('student_detail_user_id', Auth::id())
			->select('id')
			->latest('id')
			->first(); 
		})
		->where('user_allocation.user_id',Auth::id())
		->get();
		$grade = Grade::where('student_detail_user_id',Auth::id())->count();
		$type = 0;
		if ($grade > 1) {
			$type = 1;
		}
		$step = Studentsteps::where('grade_id', function($query) {
			$query->from('grades')
			->where('student_detail_user_id', Auth::id())
			->select('id')
			->latest('id')
			->first();
		})
		->where('user_id',Auth::id())
		->select('user_requirement.requirement_id')
		->get();
		$requirement = Requirement::where('user_id', function($query) {
			$query->from('user_councilor')
			->join('users','user_councilor.user_id','users.id')
			->where('user_councilor.councilor_id', function($subquery) {
				$subquery->from('user_councilor')
				->select('councilor_id')
				->where('user_id',Auth::id())
				->first();
			})
			->where('users.type','Coordinator')
			->select('users.id');
		})
		->where('type',$type)
		->select('requirements.*')
		->get();
		$userbudget = Studentsteps::join('user_budget','user_budget.user_id','user_requirement.user_id')
		->where('user_budget.budget_id', function($query) {
			$query->from('budgets')
			->where('budgets.councilor_id', function($subquery) {
				$subquery->from('user_councilor')
				->select('councilor_id')
				->where('user_id',Auth::id())
				->first();
			})
			->latest('id')
			->select('id')
			->first();
		})
		->select('user_requirement.*')
		->get();
		return view('SMS.Student.StudentIndex')->withAllocation($allocation)->withRequirement($requirement)->withUserbudget($userbudget);
	}
}
