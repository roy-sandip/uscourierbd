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
        Schema::create('agent_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('agent_id');
            $table->integer('parent_invoice_id')->nullable();
            $table->integer('child_invoice_id')->nullable();
            $table->timestamp('datetime')->useCurrent();
            $table->decimal('total_bill', 10, 2);
            $table->decimal('total_paid', 10, 2)->nullable();
            $table->decimal('total_due', 10, 2)->nullable();
            $table->string('currency')->default('BDT');
            $table->decimal('exchange_rate')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_invoices');
    }
};
