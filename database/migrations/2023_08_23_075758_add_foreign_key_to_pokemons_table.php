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
        Schema::table('pokemons', function (Blueprint $table) {
            //
             $table->foreign('race_id')->references('id')->on('races')
                  ->onDelete('cascade')->onUpdate('cascade');
        
            $table->foreign('ability_id')->references('id')->on('abilities')
                  ->onDelete('cascade')->onUpdate('cascade');
        
            $table->foreign('nature_id')->references('id')->on('natures')
                  ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokemons', function (Blueprint $table) {
            //
        });
    }
};
