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
		$setting = Setting::first();
		$setting->title = $request->title;
		$setting->year_count = $request->year;
		$setting->semester_count = $request->semester;
		$setting->save();
		return redirect()->back();
	}
}
