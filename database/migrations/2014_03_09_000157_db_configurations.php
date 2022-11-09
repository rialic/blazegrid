<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DbConfigurations extends Migration
{
    /**
     * Run the database configurations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('set session sql_require_primary_key = 0;');
    }
}
