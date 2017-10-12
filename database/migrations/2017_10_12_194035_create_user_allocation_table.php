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
			$table->integer('allocation_id')->unsigned()->index('fuserallocationallocationid_idx');
			$table->integer('grade_id')->unsigned()->index('fuser_allocation_grade_id_idx');
			$table->integer('budget_id')->unsigned()->index('fuser_allocation_budget_id_idx');
			$table->integer('receipt_id')->unsigned()->index('fuser_allocation_receipt_id_idx');
			$table->float('amount', 10, 0)->unsigned();
			$table->unique(['allocation_id','grade_id'], 'unique_idx_allocation');
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
