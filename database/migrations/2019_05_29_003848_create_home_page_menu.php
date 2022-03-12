<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomePageMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('home_page_menus'))
        {
            Schema::create('home_page_menus', function (Blueprint $table) {
                $table->increments('id');
                $table->string('href')->nullable();
                $table->string('icon')->nullable();
                $table->string('text')->nullable();
                $table->string('target')->default('_self');
                $table->string('title')->nullable();
                $table->integer('position')->default(0);
                $table->nestedSet();
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

            Schema::dropIfExists('home_page_menus');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }
}
