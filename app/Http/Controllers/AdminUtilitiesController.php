<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Setting;
class AdminUtilitiesController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('SMS.Admin.Services.AdminUtilities')->withSetting($setting);
    }
    public function store(Request $request)
    {
        return redirect()->route('adminutilities.index');
    }
}
