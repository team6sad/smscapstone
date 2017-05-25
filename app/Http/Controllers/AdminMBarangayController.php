<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Barangay;
use App\District;
use Response;
use Datatables;
use Input;
class AdminMBarangayController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function data()
    {
        $barangay = Barangay::join('districts', 'barangay.district_id','districts.id')
        ->select(['barangay.*','districts.description as districts_description']);
        return Datatables::of($barangay)
        ->addColumn('action', function ($data) {
            return "<button class='btn btn-warning btn-xs btn-detail open-modal' value='$data->id'><i class='fa fa-edit'></i> Edit</button> <button class='btn btn-danger btn-xs btn-delete' value='$data->id'><i class='fa fa-trash-o'></i> Delete</button>";
        })
        ->editColumn('isActive', function ($data) {
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
        ->rawColumns(['isActive','action'])
        ->make(true);
    }
    public function checkbox($id)
    {
        try
        {
            $barangay = Barangay::findorfail($id);
            if ($barangay->is_active) {
                $barangay->is_active=0;
            }
            else{
                $barangay->is_active=1;
            }
            $barangay->save();
        }
        catch(\Exception $e) {
            try{
                if($e->errorInfo[1]==1062)
                    return "This Data Already Exists";
                else
                    return var_dump($e->errorInfo[1]);
            }
            catch(\Exception $e){
                return "Deleted";
            }
        } 
    }
    public function index()
    {
        $district = District::where('is_active',1)->pluck('description','id');
        return view('SMS.Admin.Maintenance.AdminMBarangay')->withDistrict($district);
    }
    public function store(Request $request)
    {
        Input::merge(array_map('trim', Input::all()));
        try
        {
            $barangay = new Barangay;
            $barangay->district_id=$request->intDistID;
            $barangay->description=$request->strBaraDesc;
            $barangay->save();
            return Response::json($barangay);
        }
        catch(\Exception $e) {
            if($e->errorInfo[1]==1062)
                return "This Data Already Exists";
            else if($e->errorInfo[1]==1452)
                return "District Deleted";
            else
                return var_dump($e->errorInfo[1]);
        } 
    }
    public function edit($id)
    {
        try
        {
            $barangay = Barangay::join('districts', 'barangay.district_id','districts.id')
            ->select('barangay.*', 'districts.description as districts_description')
            ->where('barangay.id',$id)
            ->firstorfail();
            return Response::json($barangay);
        }
        catch(\Exception $e)
        {
            return "Deleted";
        }
    }
    public function update(Request $request, $id)
    {
        Input::merge(array_map('trim', Input::all()));
        try
        {
            try
            {
                $barangay = Barangay::findorfail($id);
                $barangay->district_id = $request->intDistID;
                $barangay->description = $request->strBaraDesc;
                $barangay->save();
                $barangay = Barangay::join('districts', 'barangay.district_id','districts.id')
                ->select('barangay.*', 'districts.description as districts_description')
                ->where('barangay.id',$id)
                ->first();
                return Response::json($barangay);
            }
            catch(\Exception $e) {
                if($e->errorInfo[1]==1062)
                    return "This Data Already Exists";
                else
                    return var_dump($e->errorInfo[1]);
            } 
        } 
        catch(\Exception $e) {
            return "Deleted";
        }
    }
    public function destroy($id)
    {
        try
        {
            $barangay = Barangay::findorfail($id);
            try
            {
                $barangay->delete();
                return Response::json($barangay);
            }
            catch(\Exception $e) {
                if($e->errorInfo[1]==1451)
                    return Response::json(['true',$barangay]);
                else
                    return Response::json(['true',$barangay,$e->errorInfo[1]]);
            }
        } 
        catch(\Exception $e) {
            return "Deleted";
        }
    }
}
