<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCareconsoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('careconsole', function (Blueprint $table) {
			$table->integer('priority')->unsigned()->nullable();
			$table->timestamp('archived_date')->nullable();
			$table->timestamp('recall_date')->nullable();
			$table->dropColumn('archived');
			$table->dropColumn('recall');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
