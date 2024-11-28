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
        Schema::create('cards', function (Blueprint $table) {
            $table->string('id')->primary(); // ID único de la carta
            $table->string('name'); // Nombre
            $table->string('supertype')->nullable(); // Supertipo
            $table->json('subtypes')->nullable(); // Subtipos
            $table->string('level')->nullable(); // Nivel
            $table->string('hp')->nullable(); // Puntos de vida
            $table->json('types')->nullable(); // Tipos
            $table->json('evolves_to')->nullable(); // Evoluciones
            $table->json('attacks')->nullable(); // Ataques
            $table->json('weaknesses')->nullable(); // Debilidades
            $table->json('retreat_cost')->nullable(); // Coste de retirada
            $table->integer('converted_retreat_cost')->nullable(); // Coste convertido
            $table->string('set_id'); // ID del set
            $table->string('set_name'); // Nombre del set
            $table->string('rarity')->nullable(); // Rareza
            $table->text('flavor_text')->nullable(); // Texto de sabor
            $table->json('national_pokedex_numbers')->nullable(); // Números de Pokédex
            $table->string('small_image_url')->nullable(); // Imagen pequeña
            $table->string('large_image_url')->nullable(); // Imagen grande
            $table->string('tcgplayer_url')->nullable(); // URL de TCGPlayer
            $table->string('cardmarket_url')->nullable(); // URL de CardMarket
            $table->json('prices')->nullable(); // Precios
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
