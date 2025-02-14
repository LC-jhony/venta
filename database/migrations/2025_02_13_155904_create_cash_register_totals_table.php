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
        Schema::create('cash_register_totals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cash_register_id');
            $table->decimal('sale_total', 10, 2);
            $table->decimal('purchase_total', 10, 2);
            $table->foreign('cash_register_id')->references('id')->on('cash_registers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_register_totals');
    }
};
