<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateHomeAdsPlacementsTable.
 */
class CreateHomeAdsPlacementsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		if(!Schema::hasTable('home_ads_placement_blocks'))
        {
			Schema::create('home_ads_placement_blocks', function(Blueprint $table) {
				$table->increments('id');
                $table->string('identifier')->nullable();
                $table->string('title')->nullable();
				$table->timestamps();
				$table->engine = 'InnoDB';
			});
		}

		if(!Schema::hasTable('home_ads_placement_blockables'))
        {
            Schema::create('home_ads_placement_blockables', function($table)
            {
                $table->integer('home_ads_placement_block_id');
                $table->integer('home_ads_placement_blockable_id');
                $table->string('home_ads_placement_blockable_type');
                $table->engine = 'InnoDB';

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
			Schema::dropIfExists('home_ads_placement_blocks');
			Schema::dropIfExists('home_ads_placement_blockables');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
	}
}
