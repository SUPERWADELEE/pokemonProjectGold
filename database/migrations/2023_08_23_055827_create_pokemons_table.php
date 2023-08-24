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
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('race_id');
            $table->integer('level');
            $table->unsignedBigInteger('ability_id');
            $table->unsignedBigInteger('nature_id');
            $table->json('skill');
            $table->timestamps();
            $table->softDeletes();
        
            // $table->foreign('race_id')->references('id')->on('races')
            //       ->onDelete('cascade')->onUpdate('cascade');
        
            // $table->foreign('ability_id')->references('id')->on('abilities')
            //       ->onDelete('cascade')->onUpdate('cascade');
        
            // $table->foreign('nature_id')->references('id')->on('natures')
            //       ->onDelete('cascade')->onUpdate('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemons');
    }
};
