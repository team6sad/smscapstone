<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchoolCourseTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('school_course', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('school_id')->unsigned()->index('f_school_course_school_id_idx');
			$table->integer('course_id')->unsigned()->index('f_school_course_course_id_idx');
			$table->char('year', 1);
			$table->char('semester', 1);
			$table->unique(['school_id','course_id'], 'f_school_course_span');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('school_course');
	}

}
