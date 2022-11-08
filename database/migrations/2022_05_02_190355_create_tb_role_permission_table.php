<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRolePermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_role_permission', function (Blueprint $table) {
            $table->uuid('rp_uuid')->primary();
            $table->uuid('ro_uuid');
            $table->uuid('pe_uuid');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->foreign('ro_uuid')->references('ro_uuid')->on('tb_roles')->onDelete('cascade');
            $table->foreign('pe_uuid')->references('pe_uuid')->on('tb_permissions')->onDelete('cascade');

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
        Schema::dropIfExists('tb_role_permission');
    }
}
