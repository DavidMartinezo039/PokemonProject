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
        Schema::table('user_set_cards', function (Blueprint $table) {
            $table->unsignedInteger('order_number')->nullable()->after('card_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_set_cards', function (Blueprint $table) {
            $table->dropColumn('order_number');
        });
    }
};
