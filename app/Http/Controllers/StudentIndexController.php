<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\UserAllocation;
use App\Requirement;
use App\Grade;
use Auth;
use App\Application;
use App\Connection;
use App\Allocation;
use App\Utility;
use App\UserBudget;
class StudentIndexController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('student');
	}
	public function index()
	{
		$application = Application::find(Auth::id());
		if($application->student_status == 'Graduated' || $application->student_status == 'Forfeit')
			return view('SMS.Student.StudentDeactivate');
		$councilor = Connection::join('users','user_councilor.user_id','users.id')
		->where('user_councilor.councilor_id', function($subquery) {
			$subquery->from('user_councilor')
			->select('councilor_id')
			->where('user_id',Auth::id())
			->first();
		})
		->where('users.type','Coordinator')
		->select('users.id','user_councilor.councilor_id')
		->first();
		$utility = Utility::find($councilor->id);
		$grade = Grade::where('student_detail_user_id',Auth::id())->count();
		$type = 0;
		if ($grade > 1) {
			$type = 1;
		}
		$requirement = Requirement::whereNotIn('id', function($query) use($councilor){
			$query->from('user_requirement')
			->where('budget_id', function($subquery) use($councilor){
				$subquery->from('budgets')
				->where('user_id', $councilor->id)
				->latest('id')
				->select('id')
				->first();
			})
			->select('requirement_id')
			->where('user_id',Auth::id())
			->get();
		})
		->where('is_active',1)
		->where('type', $type)
		->where('user_id',$councilor->id)
		->select('requirements.*')
		->get();
		$list = Requirement::where('user_id',$councilor->id)->where('is_active',1)->where('type', $type)->get();
		$allocation = Allocation::leftJoin('allocation_types','allocations.allocation_type_id','allocation_types.id')
		->leftJoin('user_allocation_type','allocation_types.id','user_allocation_type.allocation_type_id')
		->select('allocation_types.description','allocations.amount','allocations.id','user_allocation_type.allocation_type_id')
		->where('allocations.budget_id', function($query) use($councilor){
			$query->from('budgets')
			->where('user_id',$councilor->id)
			->select('id')
			->latest('id')
			->first();
		})
		->where('user_allocation_type.user_id',$councilor->id);
		$allocate = $allocation->get();
		$claiming = $allocation->whereNotIn('allocations.id', function($query) {
			$query->from('user_allocation')
			->join('receipts','receipts.id','user_allocation.receipt_id')
			->select('user_allocation.allocation_id')
			->where('receipts.user_id',Auth::id())
			->where('user_allocation.grade_id', function($subquery) {
				$subquery->from('grades')
				->select('id')
				->where('student_detail_user_id',Auth::id())
				->latest('id')
				->first();
			})
			->get();
		})
		->get();
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
		return view('SMS.Student.StudentIndex')->withList($list)->withRequirement($requirement)->withAllocate($allocate)->withClaiming($claiming)->withApplication($application)->withUtility($utility)->withUserbudget($userbudget);
	}
}
