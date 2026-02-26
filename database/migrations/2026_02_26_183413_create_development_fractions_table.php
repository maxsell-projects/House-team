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
        Schema::create('development_fractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('development_id')->constrained()->onDelete('cascade');
            $table->string('ref')->nullable();
            $table->string('block')->nullable();
            $table->string('floor')->nullable();
            $table->string('typology')->nullable();
            $table->decimal('abp', 10, 2)->nullable();
            $table->decimal('balcony_area', 10, 2)->nullable();
            $table->integer('parking_spaces')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->string('floor_plan_path')->nullable();
            $table->string('remax_id')->nullable();
            $table->string('status')->default('Disponível');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('development_fractions');
    }
};
