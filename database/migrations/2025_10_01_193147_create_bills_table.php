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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->integer('shipment_id')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->decimal('billed_weight',6,2)->nullable();
            $table->decimal('net_bill',8,2)->nullable();
            $table->decimal('extra_charge',8,2)->nullable();
            $table->decimal('other_charges',8,2)->nullable();
            $table->decimal('total_bill',10,2)->nullable();
            $table->decimal('total_paid',10,2)->nullable();
            $table->decimal('total_due')->nullable();
            $table->string('remark')->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->nullable( ); 
            $table->timestamps();
            $table->index('shipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billings');
    }
};
