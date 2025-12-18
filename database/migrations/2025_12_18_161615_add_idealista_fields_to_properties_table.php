<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // ID externo do Idealista (ex: "12345678")
            $table->string('idealista_id')->nullable()->unique()->after('id');
            // Link direto para o anúncio no idealista (útil para auditoria)
            $table->string('idealista_url')->nullable()->after('slug');
            // Última sincronização
            $table->timestamp('last_synced_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['idealista_id', 'idealista_url', 'last_synced_at']);
        });
    }
};