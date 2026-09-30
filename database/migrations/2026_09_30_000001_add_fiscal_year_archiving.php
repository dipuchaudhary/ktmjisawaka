<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = [
        'mudda_dartas',
        'banking_muddas',
        'patra_challanis',
        'aviyog_challanis',
        'punarabedans',
        'challanis',
        'challani_formats',
    ];

    public function up(): void
    {
        if (Schema::hasTable('fiscal_years')) {
            return;
        }

        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->unique();
            $table->unsignedInteger('start_year');
            $table->unsignedInteger('end_year');
            $table->boolean('is_current')->default(false)->index();
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });

        foreach ($this->tables as $tableName) {
            if (!Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'fiscal_year_id')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignId('fiscal_year_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('fiscal_years')
                    ->nullOnDelete();
                $table->index('fiscal_year_id');
            });
        }

        // Preserve all existing records by assigning them to the legacy fiscal year.
        $legacy = DB::table('fiscal_years')->insertGetId([
            'name' => '2082/083',
            'start_year' => 2082,
            'end_year' => 2083,
            'is_current' => false,
            'is_closed' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'fiscal_year_id')) {
                DB::table($tableName)->whereNull('fiscal_year_id')->update([
                    'fiscal_year_id' => $legacy,
                ]);
            }
        }

        // Open the next fiscal year with completely fresh transactional data.
        $current = DB::table('fiscal_years')->insertGetId([
            'name' => '2083/084',
            'start_year' => 2083,
            'end_year' => 2084,
            'is_current' => true,
            'is_closed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (Schema::hasTable('challani_formats')) {
            DB::table('challani_formats')->insert([
                'fiscal_year_id' => $current,
                'format_prefix' => '2083/084',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->tables) as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'fiscal_year_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['fiscal_year_id']);
                    $table->dropColumn('fiscal_year_id');
                });
            }
        }

        Schema::dropIfExists('fiscal_years');
    }
};