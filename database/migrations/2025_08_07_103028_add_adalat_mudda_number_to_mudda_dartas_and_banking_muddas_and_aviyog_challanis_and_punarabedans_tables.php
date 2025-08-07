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
       $tables = ['mudda_dartas', 'banking_muddas', 'aviyog_challanis','punarabedans'];
       foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->string('adalat_mudda_number')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['mudda_dartas', 'banking_muddas', 'aviyog_challanis','punarabedans'];
       foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->string('adalat_mudda_number')->nullable();
            });
        }
    }
};
