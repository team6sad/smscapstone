<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAllocationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_allocation', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_id', 30)->index('fuserallocationuserid_idx');
			$table->integer('allocation_id')->unsigned()->index('fuserallocationallocationid_idx');
			$table->integer('grade_id')->unsigned()->index('fuser_allocation_grade_id_idx');
			$table->integer('budget_id')->unsigned()->index('fuser_allocation_budget_id_idx');
			$table->dateTime('date_claimed');
			$table->float('amount', 10, 0)->unsigned();
			$table->unique(['user_id','allocation_id','grade_id'], 'unique_idx_allocation');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_allocation');
	}

}
