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
        Schema::create('banking_muddas', function (Blueprint $table) {
            $table->id();
            $table->string('anusandhan_garne_nikaye');
            $table->string('mudda_number')->nullable();
            $table->string('mudda_name');
            $table->string('jaherwala_name')->nullable();
            $table->text('pratiwadi_name')->nullable();
            $table->string('pratiwadi_number')->nullable();
            $table->string('pesi_karyala')->nullable();
            $table->string('mudda_date')->nullable();
            $table->string('mudda_myad')->nullable();
            $table->string('sarkariwakil_name')->nullable();
            $table->string('mudda_pathayko_date')->nullable();
            $table->string('challani_number')->nullable();
            $table->string('kaifiyat')->nullable();
            $table->string('user_name');
            $table->string('status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banking_muddas');
    }
};
