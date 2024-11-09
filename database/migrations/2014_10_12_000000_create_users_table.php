<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('full_name');
            $table->string('email')->unique()->collation('utf8_unicode_ci');
            $table->string('username')->unique()->collation('utf8_unicode_ci');
            $table->string('password');
            $table->double('balance')->nullable()->default(0);
            $table->string('phone_number')->nullable();
            $table->enum('level', ['public', 'silver', 'gold', 'vip'])->nullable()->default('public');
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('is_active')->default(0);
            $table->rememberToken();
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
};
