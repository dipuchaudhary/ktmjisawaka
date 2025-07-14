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
        Schema::create('mudda_dartas', function (Blueprint $table) {
            $table->id();
            $table->string('anusandhan_garne_nikaye');
            $table->string('mudda_number')->nullable();
            $table->string('mudda_name');
            $table->string('jaherwala_name');
            $table->text('pratiwadi_name')->nullable();
            $table->string('pratiwadi_number')->nullable();
            $table->string('mudda_date')->nullable();
            $table->string('mudda_suru_myad')->nullable();
            $table->string('mudda_myad_thap')->nullable();
            $table->string('jamma_din')->nullable();
            $table->string('sarkariwakil_name')->nullable();
            $table->string('faat_name')->nullable();
            $table->string('mudda_pathayko_date')->nullable();
            $table->string('kaifiyat')->nullable();
            $table->string('mudda_bibran');
            $table->string('user_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mudda_dartas');
    }
};
