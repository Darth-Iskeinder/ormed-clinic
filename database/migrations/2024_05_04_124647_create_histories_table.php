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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('staff_id');
            $table->string('referrer');
            $table->integer('referrer_reward');
            $table->string('diagnosis');
            $table->json('services');
            $table->json('reject_services');
            $table->integer('total_amount');
            $table->integer('total_staff_revenue');
            $table->integer('total_company_revenue');
            $table->boolean('paid');
            $table->timestamps('paid_date');
            $table->boolean('completed');
            $table->timestamps('completed_date');
            $table->boolean('refund');
            $table->timestamps('refund_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
