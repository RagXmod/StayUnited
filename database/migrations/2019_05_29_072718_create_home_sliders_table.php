<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateHomeSlidersTable.
 */
class CreateHomeSlidersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if(!Schema::hasTable('home_sliders'))
        {
            Schema::create('home_sliders', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title')->nullable();
                $table->string('link')->nullable();
                $table->string('name')->nullable();
                $table->string('path')->nullable();
                $table->string('size')->nullable();
                $table->integer('position')->default(0);
                $table->timestamps();
            });
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // ignore foreign
			Schema::dropIfExists('home_sliders');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
	}
}
