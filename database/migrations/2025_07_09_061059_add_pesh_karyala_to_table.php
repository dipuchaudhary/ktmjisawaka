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
        Schema::table('aviyog_challanis', function (Blueprint $table) {
            $table->string('pesh_karyala')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aviyog_challanis', function (Blueprint $table) {
            $table->string('pesh_karyala')->nullable();
        });
    }
};
