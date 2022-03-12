<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('status_identifier')->nullable();
            $table->string('app_id')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('app_link')->nullable()->comment="Link fro google play store or any site link";
            $table->string('app_image_url')->nullable()->comment="Main Image for the app";

            $table->string('current_ratings')->nullable();
            $table->string('total_ratings')->nullable();

            $table->string('seo_title')->nullable();
            $table->text('seo_keyword')->nullable();
            $table->string('seo_description')->nullable();

            $table->integer('is_cron_check')->default(0)->unsigned();
            $table->timestamps();

            $table->softDeletes();
            $table->index('app_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->engine = 'InnoDB';
        });

        Schema::create('app_more_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id')->unsigned();
            $table->string('identifier')->nullable();
            $table->string('title')->nullable();
            $table->string('value')->nullable();

            $table->integer('position')->default(0);
            $table->timestamps();

            $table->index('app_id');
            $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
            $table->engine = 'InnoDB';
        });


        Schema::create('app_developers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('identifier')->nullable();
            $table->string('slug')->nullable();
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->longText('description')->nullable();
            $table->longText('details')->nullable();


            $table->string('seo_title')->nullable();
            $table->text('seo_keyword')->nullable();
            $table->string('seo_description')->nullable();

            $table->timestamps();

            $table->softDeletes();
            $table->index('identifier');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->engine = 'InnoDB';
        });


        Schema::create('apps_developers', function (Blueprint $table) {
            $table->integer('app_id')->unsigned();
            $table->integer('app_developer_id')->unsigned();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('app_versions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('app_id')->unsigned();
            $table->string('identifier')->nullable();
            $table->string('description')->nullable();

            $table->string('file_path')->nullable();
            $table->double('size', 255, 2)->default(0);
            $table->string('original_name')->nullable();

            $table->string('download_link')->nullable();
            $table->string('is_link_external')->default(0)->comment="outside download link.";
            $table->longText('details')->nullable();

            $table->integer('position')->default(0);

            $table->string('seo_title')->nullable();
            $table->text('seo_keyword')->nullable();
            $table->string('seo_description')->nullable();

            $table->timestamps();

            $table->index('identifier');
            $table->foreign('app_id')->references('id')->on('apps')->onDelete('cascade');
            $table->engine = 'InnoDB';
        });



        Schema::create('app_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('app_imageable_id')->unsigned();
            $table->string('app_imageable_type')->nullable();
            $table->string('file_path')->nullable();
            $table->string('image_url')->nullable();
            $table->double('size', 255, 2)->default(0);
            $table->string('original_name')->nullable();
            $table->string('upload_type')->nullable();

            $table->integer('position')->default(0);
            $table->timestamps();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // ignore foreign

        Schema::dropIfExists('apps');
        Schema::dropIfExists('app_more_details');
        Schema::dropIfExists('app_developers');
        Schema::dropIfExists('apps_developers');
        Schema::dropIfExists('app_versions');
        Schema::dropIfExists('app_images');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // set foreign
    }
}
