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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('service_type', 100);
            $table->foreignId('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->foreignId('service_id')
                ->references('id')->on('services')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->foreignId('provider_id')
                ->references('id')->on('providers')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->foreignId('payment_id')
                ->references('id')->on('payments')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->string('invoice', 100);
            $table->string('data', 100);
            $table->string('additional_data', 100)->nullable();
            $table->string('additional_info')->nullable();
            $table->double('price')->default('0');
            $table->double('profit')->default('0');
            $table->double('unique_code')->default('0');
            $table->double('fee')->default('0');
            $table->tinyInteger('is_paid')->default('0');
            $table->tinyInteger('is_order_processed')->default('0');
            $table->tinyInteger('is_refund')->default('0');
            $table->enum('status', [
                'pending', 'proses', 'sukses', 'gagal', 'kadaluarsa'
            ])->default('pending');
            $table->string('ip_address', 50)->nullable();
            $table->enum('order_type', [
                'member', 'public'
            ])->default('public');
            $table->string('provider_order_id')->nullable();
            $table->text('provider_order_description')->nullable();
            $table->longText('payment_gateway_request_response')->nullable();
            $table->longText('payment_gateway_callback_response')->nullable();
            $table->string('email_order', 100)->nullable();
            $table->string('whatsapp_order', 100)->nullable();
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
        Schema::dropIfExists('orders');
    }
};
