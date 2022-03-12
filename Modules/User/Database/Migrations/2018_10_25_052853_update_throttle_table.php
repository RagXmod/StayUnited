<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateThrottleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('throttle', function (Blueprint $table) {
            $table->boolean('suspended')->default(0)->after('ip');
            $table->timestamp('suspended_at')->nullable()->after('suspended');
            $table->boolean('banned')->default(0)->after('suspended_at');
            $table->timestamp('banned_at')->nullable()->after('banned');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('throttle'))
        {
            Schema::table('throttle', function(Blueprint $table)
            {
                $table->dropColumn('suspended');
                $table->dropColumn('banned');
                $table->dropColumn('suspended_at');
                $table->dropColumn('banned_at');
            });
        }
    }
}
