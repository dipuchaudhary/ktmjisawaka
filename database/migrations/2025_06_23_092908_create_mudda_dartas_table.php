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
            $table->string('pratiwadi_name');
            $table->string('pratiwadi_number')->nullable();
            $table->string('mudda_stithi');
            $table->string('mudda_date')->nullable();
            $table->string('mudda_myad')->nullable();
            $table->string('sarkariwakil_name')->nullable();
            $table->string('faat_name')->nullable();
            $table->string('mudda_pathayko_date');
            $table->string('kaifiyat')->nullable();
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
