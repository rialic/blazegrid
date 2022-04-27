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
            $table->bigIncrements('us_id');
            $table->uuid('us_uuid');
            $table->string('us_name');
            $table->string('us_cpf', 11)->nullable()->unique();
            $table->string('us_email')->unique();
            $table->string('us_phone', 15);
            $table->string('us_whatsapp', 15)->nullable();
            $table->boolean('us_status')->default();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('us_password', 500);
            $table->boolean('us_terms_conditions');
            $table->timestamp('us_last_date_visit')->nullable();
            $table->string('us_ip', 50);
            $table->timestamp('us_inactivation_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate();

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
