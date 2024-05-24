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
        Schema::table('works', function (Blueprint $table) {
            $table->integer('customer_id')->nullable();
            $table->string('notes');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_type');
            $table->string('notes');
        });

        Schema::table('histories', function (Blueprint $table) {
            $table->string('referrer');
            $table->integer('referrer_reward');
            $table->string('diagnosis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
