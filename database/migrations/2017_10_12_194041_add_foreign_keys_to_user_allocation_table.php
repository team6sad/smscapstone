<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserAllocationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_allocation', function(Blueprint $table)
		{
			$table->foreign('budget_id', 'fuser_allocation_budget_id')->references('id')->on('budgets')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('grade_id', 'fuser_allocation_grade_id')->references('id')->on('grades')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('receipt_id', 'fuser_allocation_receipt_id')->references('id')->on('receipts')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('allocation_id', 'fuserallocationallocationid')->references('id')->on('allocations')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_allocation', function(Blueprint $table)
		{
			$table->dropForeign('fuser_allocation_budget_id');
			$table->dropForeign('fuser_allocation_grade_id');
			$table->dropForeign('fuser_allocation_receipt_id');
			$table->dropForeign('fuserallocationallocationid');
		});
	}

}
