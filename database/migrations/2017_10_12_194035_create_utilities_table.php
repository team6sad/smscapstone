<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUtilitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('utilities', function(Blueprint $table)
		{
			$table->string('user_id', 30)->index('fessaysuserid_idx');
			$table->text('essay', 65535);
			$table->boolean('phase_status')->default(0);
			$table->string('income_cap', 20)->default('35,000 and Above');
			$table->boolean('passing_grades')->default(1);
			$table->boolean('no_siblings')->default(0);
			$table->boolean('renewal_auto_accept')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('utilities');
	}

}
