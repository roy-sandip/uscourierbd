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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('awb')->unique()->nullable();
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->integer('agent_id');
            $table->integer('service_id');
            $table->string('reference')->nullable();
            $table->string('pieces')->nullable();
            $table->integer('stage')->default(0);
            $table->decimal('gross_weight', 5, 2);
            $table->decimal('billed_weight', 5, 2);
            $table->text('description')->nullable();
            $table->decimal('volumetric_weight', 5, 2)->nullable();
            $table->json('dimensions')->nullable();
            $table->timestamp('received_at')->useCurrent();
            $table->timestamp('est_delivery_date')->nullable();
            $table->string('received_by')->nullable();
            $table->integer('created_by'); //FK user_id
            $table->integer('updated_by')->nullable(); //FK user_id
            $table->timestamps();
            $table->softDeletes();         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
