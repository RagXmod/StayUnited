<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Configurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('configurations'))
        {
            Schema::create('configurations', function(Blueprint $table) {
                $table->increments('id');
                $table->string('group')->nullable();
                $table->string('identifier')->nullable();
                $table->text('value')->nullable();
                $table->string('description')->nullable();
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if (!Schema::hasTable('menus'))
        {
            Schema::create('menus', function (Blueprint $table) {
                $table->increments('id');
                $table->string('module_name', 100)->nullable();
                $table->string('identifier', 100)->nullable();
                $table->integer('parent_id')->default(0);
                $table->string('title', 100)->nullable();
                $table->text('permissions')->nullable();
                $table->integer('position')->default(0);

                $table->timestamps();
                $table->engine = 'InnoDB';
                $table->index('identifier');
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

        Schema::dropIfExists('configurations');
        Schema::dropIfExists('menus');
    }
}
