<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Course extends Model
{
	public $timestamps = false;
	public static $storeRule = [
	'strCourDesc' => 'required|unique:courses,description|max:100',
	'abbreviation' => 'required|max:10',
	];
	public static function updateRule($id)
	{
		return $rules = [
		'strCourDesc' => 'required|unique:courses,description,'.$id.'|max:100',
		'abbreviation' => 'required|max:10',
		];
	} 
}
