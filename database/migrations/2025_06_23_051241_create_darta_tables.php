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
        Schema::create('darta_tables', function (Blueprint $table) {
            $table->id();
            $table->string('anusandhan_garne_nikale');
            $table->string('mudda_number')->unique();
            $table->string('mudda_name');
            $table->string('jaherwala_name');
            $table->string('pratiwadi_name');
            $table->integer('pratiwadi_number');
            $table->string('mudda_stithi');
            $table->string('mudda_date');
            $table->string('mudda_myad');
            $table->string('sarkariwakil_name');
            $table->string('faat_name');
            $table->string('mudda_pathayko_date');
            $table->string('kaifiyat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('darta_tables');
    }
};
