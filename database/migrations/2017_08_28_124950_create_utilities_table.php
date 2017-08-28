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
			$table->boolean('renewal_status')->default(0);
			$table->boolean('apply_status')->default(0);
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
