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
        Schema::create('punarabedans', function (Blueprint $table) {
            $table->id();
            $table->string('mudda_number')->nullable();
            $table->string('jaherwala_name');
            $table->text('pratiwadi_name');
            $table->string('mudda_name');
            $table->string('faisala_date')->nullable();
            $table->string('faisala_pramanikaran_date')->nullable();
            $table->string('suchana_date')->nullable();
            $table->string('faisala_garne_nikaye')->nullable();
            $table->string('pra_kaid')->nullable();
            $table->string('pra_jariwana')->nullable();
            $table->string('pra_xatipurti')->nullable();
            $table->string('pra_bigo')->nullable();
            $table->string('pra_multabi')->nullable();
            $table->string('faisala_kaid')->nullable();
            $table->string('faisala_jariwana')->nullable();
            $table->string('faisala_xatipurti')->nullable();
            $table->string('faisala_bigo')->nullable();
            $table->string('punarabedan')->nullable();
            $table->string('punarabedan_date')->nullable();
            $table->string('punarabedan_challani_number')->nullable();
            $table->text('nirnaye')->nullable();
            $table->string('nirnaye_date')->nullable();
            $table->string('sarkariwakil_name')->nullable();
            $table->string('kaifiyat')->nullable();
            $table->boolean('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('punarabedans');
    }
};
