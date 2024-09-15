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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_type_id')->nullable();
            $table->foreign('user_type_id')->references('id')->on('user_types')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username');
            $table->string('password');
            $table->rememberToken();
            $table->boolean('active')->default(true);
            $table->string('created_by')->nullable();
            $table->string('moidified_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
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
