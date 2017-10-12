<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserBudgetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_budget', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('user_id', 30)->index('f_user_budget_user_id_idx');
			$table->integer('budget_id')->unsigned()->index('f_user_budget_budget_id_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_budget');
	}

}
