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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('service_type', 100);
            $table->foreignId('service_category_id')
                ->references('id')->on('service_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->foreignId('provider_id')
                ->references('id')->on('providers')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->string('provider_service_code');
            $table->string('name');
            $table->string('brand', 100)->nullable();
            $table->longText('price');
            $table->longText('profit_type');
            $table->longText('profit');
            $table->longText('profit_config');
            $table->mediumText('description')->nullable();
            $table->tinyInteger('is_rate_coin')->default('0');
            $table->double('rate_coin')->default('0');
            $table->double('price_rate_coin')->default('0');
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
        Schema::dropIfExists('services');
    }
};
