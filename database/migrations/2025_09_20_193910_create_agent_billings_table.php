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
        Schema::create('agent_billings', function (Blueprint $table) {
            $table->id();
            $table->integer('shipment_id');
            $table->decimal('net_bill',8,2)->nullable();
            $table->decimal('extra_charge',8,2)->nullable();
            $table->decimal('total_bill',8,2)->nullable();
            $table->decimal('total_paid',8,2)->nullable();
            $table->decimal('total_due')->nullable();
            $table->string('remark')->nullable();
            $table->string('status')->default('pending'); 
            $table->integer('invoice_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_billings');
    }
};
