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
        Schema::create('mutation_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade')
                ->nullable();
            $table->enum('type', ['debit', 'credit']);
            $table->enum('category', ['deposit', 'order', 'refund', 'upgrade-level', 'others']);
            $table->text('description');
            $table->double('amount')->default('0');
            $table->double('beginning_balance')->default('0');
            $table->double('last_balance')->default('0');
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
        Schema::dropIfExists('mutation_balances');
    }
};
