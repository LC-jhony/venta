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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo');
            $table->string('commercial_name');
            $table->string('company_name');
            $table->string('type_company');
            $table->string('ruc');
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('web')->nullable();
            $table->string('district')->nullable();
            $table->string('department')->nullable();
            $table->string('province')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
