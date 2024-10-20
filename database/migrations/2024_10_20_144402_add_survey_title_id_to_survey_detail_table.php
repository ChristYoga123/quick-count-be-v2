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
        Schema::table('survey_details', function (Blueprint $table) {
            $table->unsignedBigInteger('survey_title_id')->nullable();
            $table->foreign('survey_title_id')->references('id')->on('survey_titles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_details', function (Blueprint $table) {
            //
        });
    }
};
