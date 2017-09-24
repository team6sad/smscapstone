<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserBudgetTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_budget', function(Blueprint $table)
		{
			$table->foreign('budget_id', 'f_user_budget_budget_id')->references('id')->on('budgets')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('user_id', 'f_user_budget_user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_budget', function(Blueprint $table)
		{
			$table->dropForeign('f_user_budget_budget_id');
			$table->dropForeign('f_user_budget_user_id');
		});
	}

}
