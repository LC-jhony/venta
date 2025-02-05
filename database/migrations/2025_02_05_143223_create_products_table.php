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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('bar_code');
            $table->string('image')->nullable();
            $table->string('name', 200)->nullable();
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->decimal('sales_price', 12, 2)->nullable();
            $table->integer('stock')->nullable();
            $table->integer('stock_minimum');
            $table->enum('unit_measure', ['Unidad', 'Kilo', 'Libra', 'Gramo', 'Litro', 'Mililitro', 'Metro', 'Pulgada', 'Rollo', 'Galon', 'Bolsa', 'Caja'])->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('status')->nullable();
            $table->date('expiration');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
