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
        Schema::create('shipment_updates', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedInteger('shipment_id');
            $table->string('activity')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('datetime')->useCurrent();
            $table->boolean('is_public')->default(true);
            $table->timestamp('published_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_updates');
    }
};
