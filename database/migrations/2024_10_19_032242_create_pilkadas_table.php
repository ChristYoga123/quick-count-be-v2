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
        Schema::create('pilkadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dapil_id')->constrained('dapils');
            $table->string('kelurahan');
            $table->string('tps');
            $table->integer('hasil_suara_tidak_sah');
            $table->integer('jumlah_dpt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pilkadas');
    }
};
