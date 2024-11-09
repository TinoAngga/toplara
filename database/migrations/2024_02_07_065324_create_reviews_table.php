<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_category_id')
                ->nullable()
                ->references('id')->on('service_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('service_id')
                ->nullable()
                ->references('id')->on('services')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('order_id')
                ->nullable()
                ->references('id')->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('invoice')->nullable();
            $table->integer('rating')->default('5');
            $table->text('comment')->nullable();
            $table->tinyInteger('is_published')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
