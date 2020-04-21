<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnableEventScheduler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.default') == 'sqlite') { return;}

        DB::statement("SET GLOBAL event_scheduler = ON;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('database.default') == 'sqlite') { return;}

        DB::statement("SET GLOBAL event_scheduler = OFF;");
    }
}
