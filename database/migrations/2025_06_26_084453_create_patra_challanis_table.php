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
            $table->string('challani_date')->nullable();
            $table->string('challani_number')->nullable();
            $table->string('mudda_number')->nullable();
            $table->string('challani_subject')->nullable();
            $table->string('jaherwala')->nullable();
            $table->text('pratiwadi_name')->nullable();
            $table->string('bodartha')->nullable();
            $table->string('verified_by')->nullable();
            $table->string('kaifiyat')->nullable();
            $table->string('challani_sakha');
            $table->string('faat')->nullable();
            $table->string('user_name');
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
