<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('users'))
        {
            Schema::table('users', function($table)
            {
                $table->string('username')->after('id')->nullable();
                $table->integer('is_test_mode_account')->after('last_name')->default(0)->comment = '(1 or 0. Ex. if this was set to 1, then all the orders, created by this user will not count as real order specially in accounting page etc.)';
            });
        }

        if(!Schema::hasTable('user_details'))
        {
            Schema::create('user_details', function($table)
            {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->longText('about_me')->nullable();

                $table->timestamps();
                $table->engine = 'InnoDB';
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index('user_id');
            });
        }

        if(!Schema::hasTable('user_uploads'))
        {
            Schema::create('user_uploads', function($table)
            {
                $table->increments('id');
                $table->integer('user_id')->unsigned();
                $table->integer('uploadable_id')->default(0);
                $table->string('uploadable_type')->nullable();
                $table->string('name', 100)->nullable();
                $table->string('path')->nullable();
                $table->double('size', 255, 2)->nullable();
                $table->string('upload_type', 50)->nullable();
                $table->string('other_type', 50)->nullable();
                $table->boolean('position')->default(1);

                $table->timestamps();
                $table->engine = 'InnoDB';
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['user_id']);
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
            Schema::dropIfExists('user_details');
            Schema::dropIfExists('user_uploads');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // ignore foreign
    }
}
