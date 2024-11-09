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
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('service_type', 100);
            $table->string('name')->unique()->collation('utf8_unicode_ci');
            $table->string('slug')->nullable()->collation('utf8_unicode_ci');
            $table->string('brand', 100)->nullable();
            $table->string('get_nickname_code')->nullable();
            $table->string('img');
            $table->string('guide_img')->nullable();
            $table->longText('description')->nullable();
            $table->longText('information')->nullable();
            $table->tinyInteger('is_additional_data')->default('0');
            $table->tinyInteger('is_check_id')->default('0');
            $table->tinyInteger('is_active')->default('0');
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
        Schema::dropIfExists('service_categories');
    }
};
