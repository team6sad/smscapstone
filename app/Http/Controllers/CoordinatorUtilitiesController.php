<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Application;
use Auth;
use DB;
use App\Requirement;
use Response;
use App\Allocation;
use App\Grade;
use App\Studentsteps;
use App\Allocatebudget;
use App\Budgtype;
use App\UserAllocationType;
use App\Utility;
use Datatables;
class CoordinatorUtilitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('coordinator');
    }
    public function index()
    {
        $utility = Utility::where('user_id',Auth::id())->first();
        return view('SMS.Coordinator.Services.CoordinatorUtilities')->withUtility($utility);
    }
    public function utility(Request $request)
    {
        $utility = Utility::find(Auth::id());
        $utility->essay = $request->essay;
        $utility->income_cap = $request->income_cap;
        if (is_null($request->passing_grades))
            $utility->passing_grades = 0;
        else 
            $utility->passing_grades = 1;
        if (is_null($request->no_siblings))
            $utility->no_siblings = 0;
        else
            $utility->no_siblings = 1;
        if (is_null($request->renewal_auto_accept))
            $utility->renewal_auto_accept = 0;
        else
            $utility->renewal_auto_accept = 1;
        $utility->save();
        return redirect(route('coordinatorutilities.index'));
    }
}
