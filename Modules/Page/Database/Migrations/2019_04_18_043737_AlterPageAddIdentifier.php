<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPageAddIdentifier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('pages'))
        {
            Schema::table('pages', function($table)
            {
                $table->string('identifier')->after('status_identifier')->nullable();
                $table->string('icon')->after('content')->nullable();
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
        if (Schema::hasTable('pages'))
        {
            Schema::table('pages', function(Blueprint $table)
            {
                $table->dropColumn('identifier');
                $table->dropColumn('icon');
            });
        }
    }
}
