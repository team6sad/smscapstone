<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserRequirementTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_requirement', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_id', 30)->index('fstudent_steps_user_id_idx');
			$table->integer('requirement_id')->unsigned()->index('fstudent_steps_step_id_idx');
			$table->integer('grade_id')->unsigned()->index('fstudent_steps_grade_id_idx');
			$table->integer('budget_id')->unsigned()->index('fstudent_steps_budget_id_idx');
			$table->dateTime('date_passed');
			$table->unique(['user_id','requirement_id','grade_id'], 'unique_idx_requirements');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_requirement');
	}

}
