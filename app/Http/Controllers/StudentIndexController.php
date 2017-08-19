<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\UserAllocation;
use Auth;
class StudentIndexController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('student');
	}
	public function index()
	{
		$allocation = UserAllocation::join('allocations','allocations.id','user_allocation.allocation_id')
		->join('allocation_types','allocations.allocation_type_id','allocation_types.id')
		->select('allocation_types.description','allocations.amount','user_allocation.id','user_allocation.date_claimed')
		->where('allocations.budget_id', function($query){
			$query->from('budgets')
			->select('id')
			->latest('id')
			->first();
		})
		->where('user_allocation.user_id',Auth::id())
		->get();
		return view('SMS.Student.StudentIndex')->withAllocation($allocation);
	}
}
