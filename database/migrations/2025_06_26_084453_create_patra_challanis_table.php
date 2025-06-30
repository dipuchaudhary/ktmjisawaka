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
        Schema::create('patra_challanis', function (Blueprint $table) {
            $table->id();
            $table->string('karyalaya_name');
            $table->string('challani_date');
            $table->string('challani_number');
            $table->string('mudda_number')->nullable();
            $table->string('challani_subject');
            $table->string('bodartha')->nullable();
            $table->string('verified_by')->nullable();
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
        Schema::dropIfExists('patra_challanis');
    }
};
