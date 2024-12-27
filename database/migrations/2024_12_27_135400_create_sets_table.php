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
        Schema::create('sets', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('series')->nullable();
            $table->integer('printedTotal')->nullable();
            $table->integer('total')->nullable();
            $table->json('legalities')->nullable();
            $table->string('ptcgoCode')->nullable();
            $table->string('releaseDate')->nullable(); //fecha en la que se lanza el set
            $table->string('updatedAt')->nullable(); //fecha en la que se actualiza el set
            $table->json('images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets');
    }
};
