<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSchoolCourseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('school_course', function(Blueprint $table)
		{
			$table->foreign('course_id', 'f_school_course_course_id')->references('id')->on('courses')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('school_id', 'f_school_course_school_id')->references('id')->on('schools')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('school_course', function(Blueprint $table)
		{
			$table->dropForeign('f_school_course_course_id');
			$table->dropForeign('f_school_course_school_id');
		});
	}

}
