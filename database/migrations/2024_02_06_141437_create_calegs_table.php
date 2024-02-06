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
        Schema::create('calegs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partai_id')->constrained();
            $table->foreignId('dapil_id')->constrained();
            $table->string('nama');
            $table->string('no_urut');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calegs');
    }
};
