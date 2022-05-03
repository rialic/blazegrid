<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRoleUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_role_user', function (Blueprint $table) {
            $table->unsignedBigInteger('ro_id');
            $table->unsignedBigInteger('us_id');

            $table->foreign('ro_id')->references('ro_id')->on('tb_roles')->onDelete('cascade');
            $table->foreign('us_id')->references('us_id')->on('tb_users')->onDelete('cascade');

            $table->primary(['ro_id', 'us_id']);

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_role_user');
    }
}
