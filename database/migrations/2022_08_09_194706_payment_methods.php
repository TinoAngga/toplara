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
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'saldo', 'bank_transfer', 'qris', 'virtual_account', 'convience_store'
            ])->default('saldo');
            $table->string('name')->unique();
            $table->string('slug')->nullable();
            $table->double('fee')->default(0);
            $table->double('fee_percent')->default(0);
            $table->string('img')->nullable();
            $table->longText('description')->nullable();
            $table->longText('information')->nullable();
            $table->string('payment_gateway', 50)->nullable();
            $table->string('payment_gateway_code', 50)->nullable();
            $table->string('time_used', 50)->nullable();
            $table->string('time_stopped', 50)->nullable();
            $table->tinyInteger('is_qrcode')->default('0')->nullable();
            $table->mediumText('qrcode')->nullable();
            $table->tinyInteger('is_manual')->default('0')->nullable();
            $table->tinyInteger('is_public')->default('0')->nullable();
            $table->tinyInteger('is_active')->default('0')->nullable();
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
        //
    }
};
