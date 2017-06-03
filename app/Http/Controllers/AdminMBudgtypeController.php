<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Budgtype;
use Response;
use Datatables;
use Validator;
class AdminMBudgtypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    public function data()
    {   
        $budgtype = Budgtype::all();
        return Datatables::of($budgtype)
        ->addColumn('action', function ($data) {
            return "<button class='btn btn-warning btn-xs btn-detail open-modal' value='$data->id'><i class='fa fa-edit'></i> Edit</button> <button class='btn btn-danger btn-xs btn-delete' value='$data->id'><i class='fa fa-trash-o'></i> Delete</button>";
        })
        ->editColumn('is_active', function ($data) {
            $checked = '';
            if($data->is_active==1){
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
    public function checkbox($id)
    {
        try {
            $budgtype = Budgtype::findorfail($id);
            if ($budgtype->is_active) {
                $budgtype->is_active=0;
            }
            else{
                $budgtype->is_active=1;
            }
            $budgtype->save();
        } catch(\Exception $e) {
            return "Deleted";
        } 
    }
    public function index()
    {
        return view('SMS.Admin.Maintenance.AdminMBudgtype');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), Budgtype::$storeRule);
        if ($validator->fails()) {
            return Response::json($validator->errors()->first(), 422);
        }
        try {
            $budgtype = new Budgtype;
            $budgtype->description=$request->strTypeDesc;
            $budgtype->save();
            return Response::json($budgtype);
        } catch(\Exception $e) {
            return var_dump($e->errorInfo[1]);
        } 
    }
    public function edit($id)
    {
        try {
            $budgtype = Budgtype::findorfail($id);
            return Response::json($budgtype);
        } catch(\Exception $e) {
            return "Deleted";
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), Budgtype::updateRule($id));
        if ($validator->fails()) {
            return Response::json($validator->errors()->first(), 422);
        }
        try {
            try {
                $budgtype = Budgtype::findorfail($id);
                $budgtype->description=$request->strTypeDesc;
                $budgtype->save();
                return Response::json($budgtype);
            } catch(\Exception $e) {
                return var_dump($e->errorInfo[1]);
            }
        } catch(\Exception $e) {
            return Response::json("The record is invalid or deleted.", 422);
        }
    }
    public function destroy($id)
    {
        try {
            $budgtype = Budgtype::findorfail($id);
            try {
                $budgtype->delete();
                return Response::json($budgtype);
            } catch(\Exception $e) {
                if($e->errorInfo[1]==1451)
                    return Response::json(['true',$budgtype]);
                else
                    return Response::json(['true',$budgtype,$e->errorInfo[1]]);
            }
        } catch(\Exception $e) {
            return "Deleted";
        }
    }
}
