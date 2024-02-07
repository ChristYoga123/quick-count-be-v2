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
        Schema::create('suara_pillegs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pilleg_id')->constrained('pillegs');
            $table->foreignId('caleg_id')->constrained('calegs');
            $table->integer('jumlah_suara');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suara_pillegs');
    }
};
