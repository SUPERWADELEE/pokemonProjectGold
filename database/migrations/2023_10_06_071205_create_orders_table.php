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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('total_price');
            $table->unsignedBigInteger('payment_status')->comment('支付狀態:0=已取消,1=未支付,2=已支付');
            $table->unsignedBigInteger('payment_method')->comment('支付方式:0=信用卡,1=貨到付款');
            $table->unsignedBigInteger('status')->comment('訂單狀態:0=待處理,1=已處理');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
