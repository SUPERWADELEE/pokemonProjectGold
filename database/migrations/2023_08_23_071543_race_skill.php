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
        Schema::create('race_skill', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('race_id');
        $table->unsignedBigInteger('skill_id');

        $table->foreign('race_id')->references('id')->on('races')->onDelete('cascade')->onUpdate('cascade');
        $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade')->onUpdate('cascade');


    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
