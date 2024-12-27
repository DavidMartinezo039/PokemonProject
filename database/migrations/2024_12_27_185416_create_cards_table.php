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
        // Tabla principal de cartas
        Schema::create('cards', function (Blueprint $table) {
            $table->string('id')->primary();  // Usamos 'string' para el id
            $table->string('name');
            $table->foreignId('supertype_id')->constrained('supertypes')->onDelete('cascade'); // Relación con 'supertypes'
            $table->string('level')->nullable(); // Solo para Pokémon
            $table->string('hp')->nullable(); // Solo para Pokémon
            $table->string('evolvesFrom')->nullable(); // Evoluciona de (solo para Pokémon)
            $table->json('evolvesTo')->nullable(); // Evoluciona a (solo para Pokémon)
            $table->json('rules')->nullable(); // Reglas adicionales
            $table->json('ancientTrait')->nullable(); // Rasgo antiguo (si aplica)
            $table->json('abilities')->nullable(); // Habilidades (solo para Pokémon)
            $table->json('attacks')->nullable(); // Ataques (solo para Pokémon)
            $table->json('weaknesses')->nullable(); // Debilidades (solo para Pokémon)
            $table->json('resistances')->nullable(); // Resistencias (solo para Pokémon)
            $table->json('retreatCost')->nullable(); // Coste de retirada (solo para Pokémon)
            $table->integer('convertedRetreatCost')->nullable(); // Coste de retirada convertido (solo para Pokémon)
            $table->string('set_id');
            $table->foreign('set_id')->references('id')->on('sets')->onDelete('cascade'); // Relación con 'sets'
            $table->string('number')->nullable(); // Número de la carta en el set
            $table->string('artist')->nullable(); // Artista de la carta
            $table->foreignId('rarity_id')->constrained('rarities')->onDelete('cascade'); // Relación con 'rarities'
            $table->string('flavorText')->nullable(); // Texto de sabor (descripción de la carta)
            $table->json('nationalPokedexNumbers')->nullable(); // Números del Pokédex nacional (solo para Pokémon)
            $table->json('legalities')->nullable(); // Legalidades en diferentes formatos
            $table->string('regulationMark')->nullable(); // Marca de regulación (si aplica)
            $table->json('images')->nullable(); // Imágenes de la carta
            $table->json('tcgplayer')->nullable(); // Información de TCGPlayer
            $table->json('cardmarket')->nullable(); // Información de Cardmarket
            $table->timestamps();
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
