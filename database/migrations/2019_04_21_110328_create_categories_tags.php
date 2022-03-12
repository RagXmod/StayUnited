<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasTable('categories'))
        {
            Schema::create('categories', function($table){
                $table->increments('id');
                $table->string('identifier')->nullable();
                $table->string('status_identifier')->default('active');
                $table->string('slug')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();

                $table->string('seo_title')->nullable();
                $table->text('seo_keyword')->nullable();
                $table->string('seo_description')->nullable();
                $table->string('icon')->nullable();

                $table->integer('is_enabled')->default(1);
                $table->integer('is_featured')->default(0);
                $table->bigInteger('views')->default(0);
                $table->integer('is_demo')->default(0);
                $table->nestedSet();
                $table->timestamps();
                $table->index('identifier');
                $table->engine = 'InnoDB';


            });
        }

        if(!Schema::hasTable('categoreables'))
        {
            Schema::create('categoreables', function($table)
            {
                $table->integer('category_id');
                $table->integer('categoreable_id');
                $table->string('categoreable_type');
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

            Schema::dropIfExists('categories');
            Schema::dropIfExists('categoreables');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }
}
