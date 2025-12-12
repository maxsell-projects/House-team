<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('Apartamento');
            $table->string('status')->default('Venda');
            
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('area_gross', 10, 2)->nullable();
            $table->decimal('area_useful', 10, 2)->nullable();
            $table->decimal('area_land', 10, 2)->nullable();

            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('garages')->nullable();
            $table->string('floor')->nullable(); 
            $table->string('orientation')->nullable();
            $table->year('built_year')->nullable();
            $table->string('condition')->default('used');
            $table->string('energy_rating')->nullable();

            $table->boolean('has_lift')->default(false);
            $table->boolean('has_garden')->default(false);
            $table->boolean('has_pool')->default(false);
            $table->boolean('has_terrace')->default(false);
            $table->boolean('has_balcony')->default(false);
            $table->boolean('has_air_conditioning')->default(false);
            $table->boolean('has_heating')->default(false);
            $table->boolean('is_accessible')->default(false);
            $table->boolean('is_furnished')->default(false);
            $table->boolean('is_kitchen_equipped')->default(false);

            $table->string('cover_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('whatsapp_number')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('is_visible')->default(true);
            
            $table->timestamps();
        });

        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('path');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
    }
};