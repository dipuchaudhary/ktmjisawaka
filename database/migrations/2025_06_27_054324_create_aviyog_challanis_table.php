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
            $table->string('challani_date')->nullable();
            $table->string('challani_number')->nullable();
            $table->string('jaherwala_name');
            $table->string('pratiwadi_name');
            $table->string('mudda_name');
            $table->json('gender')->nullable();
            $table->string('gender_counts')->nullable();
            $table->string('mudda_number')->nullable();
            $table->string('sarkariwakil_name')->nullable();
            $table->string('anusandhan_garne_nikaye');
            $table->string('faat_name')->nullable();
            $table->string('file')->nullable();
            $table->string('kaifiyat')->nullable();
            $table->string('user_name')->nullable();
            $table->boolean('status')->default(false);
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
