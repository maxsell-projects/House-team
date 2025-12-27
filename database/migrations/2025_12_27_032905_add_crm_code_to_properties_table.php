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
        Schema::table('properties', function (Blueprint $table) {
            // Adiciona o campo crm_code logo após o idealista_id (ou id, se preferir)
            // Indexado para facilitar buscas futuras pelo código
            $table->string('crm_code')
                  ->nullable()
                  ->after('idealista_id')
                  ->index()
                  ->comment('Código de referência do imóvel para integração com CRM HighLevel');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('crm_code');
        });
    }
};