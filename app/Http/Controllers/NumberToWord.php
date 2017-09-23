<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumberToWord extends Controller
{
	public function number($number)
	{
		if ($number == 1) {
			return "First";
		} elseif ($number == 2) {
			return "Second";
		} elseif ($number == 3) {
			return "Third";
		} elseif ($number == 4) {
			return "Fourth";
		} elseif ($number == 5) {
			return "Fifth";
		}
	}
}
