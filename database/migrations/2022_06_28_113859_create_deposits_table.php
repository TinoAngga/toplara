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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->foreignId('payment_id')
                ->references('id')->on('payments')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->string('invoice', 100);
            $table->double('amount')->default('0');
            $table->double('unique_code')->default('0');
            $table->double('fee')->default('0');
            $table->tinyInteger('is_paid')->default('0');
            $table->enum('status', [
                'pending', 'sukses', 'gagal', 'kadaluarsa'
            ])->default('pending');
            $table->string('additional_data', 100)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->longText('payment_gateway_request_response')->nullable();
            $table->longText('payment_gateway_callback_response')->nullable();
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
        Schema::dropIfExists('deposits');
    }
};
