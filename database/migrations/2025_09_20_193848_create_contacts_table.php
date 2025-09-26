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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('attn')->nullable();
            $table->text('street');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->indexed();
            $table->string('primary_contact');
            $table->string('alt_contact')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->default('other');
            $table->timestamps();
            $table->index('country'); 
            $table->index('primary_contact'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
