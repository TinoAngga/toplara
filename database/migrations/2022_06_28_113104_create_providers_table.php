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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->collation('utf8_unicode_ci');
            $table->string('api_username')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_additional')->nullable();
            $table->string('api_url_order')->nullable();
            $table->string('api_url_status')->nullable();
            $table->string('api_url_service')->nullable();
            $table->string('api_url_profile')->nullable();
            $table->double('api_balance')->nullable()->default('0');
            $table->double('api_balance_alert')->nullable()->default('10000');
            $table->tinyInteger('is_auto_update')->default(0);
            $table->tinyInteger('is_manual')->default(0);
            $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('providers');
    }
};
