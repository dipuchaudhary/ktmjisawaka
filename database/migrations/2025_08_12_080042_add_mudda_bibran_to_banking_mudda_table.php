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
        Schema::table('banking_muddas', function (Blueprint $table) {
           $table->string('mudda_bibran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banking_muddas', function (Blueprint $table) {
          $table->string('mudda_bibran');
        });
    }
};
