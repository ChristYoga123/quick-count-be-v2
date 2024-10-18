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
        Schema::create('survey_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surveyor_id');
            $table->foreign('surveyor_id')->references('id')->on('users');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->string('nama_responden');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_details');
    }
};
