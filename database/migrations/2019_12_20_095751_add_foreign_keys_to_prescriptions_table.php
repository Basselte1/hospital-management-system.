<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPrescriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('SQLSTATE[HY000]: General error: 1 table prescriptions has no column named parasitologie
insert into "prescriptions" ("hematologie", "hemostase", "biochimie", "hormonologie", "marqueurs", "bacteriologie", "spermiologie", "urines", "serologie", "parasitologie', function(Blueprint $table)
		{
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('patient_id', '1')->references('id')->on('patients')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('prescriptions', function(Blueprint $table)
		{
			$table->dropForeign('prescriptions_user_id_foreign');
			$table->dropForeign('1');
		});
	}

}
