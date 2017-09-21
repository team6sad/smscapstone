<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Credit extends Model
{
	protected $table = 'school_course';
	public $timestamps = false;
	public static $storeRule = [
		'school_id' => 'exists:schools,id|unique_with:school_course, school_id, course_id',
		'course_id' => 'exists:courses,id',
	];
	public static function updateRule($id)
	{
		return $rules = [
			'school_id' => 'exists:schools,id|unique_with:school_course, school_id, course_id,'.$id,
			'course_id' => 'exists:courses,id',
		];
	} 
}