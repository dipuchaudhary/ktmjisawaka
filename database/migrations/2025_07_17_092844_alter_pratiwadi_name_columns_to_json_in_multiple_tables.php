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
        $tables = ['mudda_dartas', 'banking_muddas', 'aviyog_challanis', 'patra_challanis','punarabedans'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->mediumText('pratiwadi_name')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['mudda_dartas', 'banking_muddas', 'aviyog_challanis', 'patra_challanis','punarabedans'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->text('pratiwadi_name')->change();
            });
        }
    }
};
