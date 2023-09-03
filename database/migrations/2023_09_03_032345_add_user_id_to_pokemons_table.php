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
            $table->unsignedBigInteger('user_id')->nullable()->after('id');  // 使用nullable()確保現有的紀錄不會產生錯誤

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pokemons', function (Blueprint $table) {
            $table->dropColumn('user_id');     // 刪除 user_id 欄位
        });
    }
};
