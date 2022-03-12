<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterAppMoreDetailsAdjustValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('app_more_details'))
        {
            DB::statement('ALTER TABLE `dcm_app_more_details` CHANGE `value` `value` LONGTEXT  CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci  NULL;');
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `dcm_app_more_details` CHANGE `value` `value` VARCHAR(191) CHARACTER SET utf8mb4  COLLATE utf8mb4_unicode_ci  NULL ;');
    }
}
