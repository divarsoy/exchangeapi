<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabaseEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.default') == 'sqlite') { return;}

        DB::connection()->getpdo()->exec("
                CREATE EVENT IF NOT EXISTS `pruneExchangeCache`
                    ON SCHEDULE EVERY 1 SECOND
                    DO
                        DELETE FROM `exchangecache` WHERE (`created_at` + `expiration`) < NOW();"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('database.default') == 'sqlite') { return;}

        DB::connection()->getpdo()->exec("DROP EVENT IF EXISTS `pruneExchangeCache`;");
    }
}
