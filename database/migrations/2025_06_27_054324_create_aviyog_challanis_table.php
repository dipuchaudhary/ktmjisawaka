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
        Schema::create('aviyog_challanis', function (Blueprint $table) {
            $table->id();
            $table->string('challani_date');
            $table->string('challani_number');
            $table->string('jaherwala_name');
            $table->string('pratiwadi_name');
            $table->string('mudda_name');
            $table->string('gender');
            $table->string('mudda_number')->nullable();
            $table->string('sarkariwakil_name')->nullable();
            $table->string('anusandhan_garne_nikaye');
            $table->string('faat_name')->nullable();
            $table->string('file');
            $table->string('kaifiyat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aviyog_challanis');
    }
};
