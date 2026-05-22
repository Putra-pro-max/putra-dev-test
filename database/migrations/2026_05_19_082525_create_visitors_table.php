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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            
            // 💡 1. Tambahkan ->index() di sini agar query WHERE ip_address di middleware berjalan kilat
            $table->string('ip_address')->index();
            
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            
            // 💡 2. Berikan default 'Unknown' agar cocok dengan logika di middleware kamu
            $table->string('browser')->default('Unknown');
            $table->string('os')->default('Unknown');
            
            $table->string('page')->default('/');
            $table->timestamps();

            // 💡 3. Tambahkan index untuk created_at karena kita melakukan filter ->whereDate('created_at', ...)
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};  