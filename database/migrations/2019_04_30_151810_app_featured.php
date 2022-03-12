<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppFeatured extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('app_featured_posts'))
        {
            Schema::create('app_featured_posts', function($table){
                $table->increments('id');
                $table->string('status_identifier')->nullable();
                $table->string('title')->nullable();
                $table->string('slug')->nullable();
                $table->text('description')->nullable();

                $table->string('seo_title')->nullable();
                $table->text('seo_keyword')->nullable();
                $table->string('seo_description')->nullable();
                $table->string('icon')->nullable();

                $table->timestamps();
                $table->engine = 'InnoDB';
            });
        }

        if(!Schema::hasTable('app_featured_postsables'))
        {
            Schema::create('app_featured_postables', function($table){
                $table->increments('id');
                $table->integer('user_id')->nullable();
                $table->integer('app_featured_post_id');
                $table->integer('app_featured_postable_id')->unsigned();
                $table->string('app_featured_postable_type')->nullable();

                $table->integer('position')->default(0);
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

            Schema::dropIfExists('app_featured_posts');
            Schema::dropIfExists('app_featured_postables');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }
}
