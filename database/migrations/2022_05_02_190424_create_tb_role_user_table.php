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
            $table->uuid('ru_uuid')->primary();
            $table->foreignUuid('ro_uuid');
            $table->foreignUuid('us_uuid');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('ro_uuid')->references('ro_uuid')->on('tb_roles')->onDelete('cascade');
            $table->foreign('us_uuid')->references('us_uuid')->on('tb_users')->onDelete('cascade');

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
