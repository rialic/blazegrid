<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_users', function (Blueprint $table) {
            $table->uuid('us_uuid')->primary();
            $table->string('us_socialite_id');
            $table->string('us_name');
            $table->string('us_cpf', 11)->nullable()->unique();
            $table->string('us_email')->unique();
            $table->string('us_phone', 15)->nullable();
            $table->string('us_whatsapp', 15)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('us_password', 500);
            $table->boolean('us_terms_conditions');
            $table->timestamp('us_last_date_visit')->nullable();
            $table->ipAddress('us_ip')->nullable();
            $table->boolean('us_status')->default(true);
            $table->timestamp('us_inactivation_date')->nullable();
            $table->timestamp('us_expiration_plan_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

            $table->uuid('pl_uuid')->nullable();

            $table->foreign('pl_uuid')->references('pl_uuid')->on('tb_plans');

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
        Schema::dropIfExists('users');
    }
}
