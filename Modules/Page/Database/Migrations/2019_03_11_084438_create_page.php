<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if( !Schema::hasTable('pages') )
        {
            Schema::create('pages', function($table){
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->string('status_identifier');
                $table->integer('page_id')->default(0);
                $table->string('slug')->nullable();
                $table->string('title')->nullable();
                $table->longText('content')->nullable();

                $table->string('seo_title')->nullable();
                $table->string('seo_keyword')->nullable();
                $table->string('seo_description')->nullable();

                $table->integer('position')->default(0);
                $table->integer('is_enabled')->default(1);
                $table->integer('is_demo')->default(0);
                $table->timestamps();

                $table->softDeletes();
                $table->index('slug');
                // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
            Schema::dropIfExists('pages');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }
}
