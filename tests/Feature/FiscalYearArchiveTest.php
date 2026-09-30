<?php

namespace Tests\Feature;

use App\Models\FiscalYear;
use App\Models\MuddaDarta;
use App\Support\FiscalYearContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class FiscalYearArchiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_records_are_isolated_by_fiscal_year(): void
    {
        $old = FiscalYear::create([
            'name' => '2082/083',
            'start_year' => 2082,
            'end_year' => 2083,
            'is_current' => true,
        ]);

        FiscalYearContext::set($old->id);

        $oldRecord = MuddaDarta::create([
            'anusandhan_garne_nikaye' => 'पुरानो',
            'mudda_name' => 'पुरानो मुद्दा',
            'jaherwala_name' => 'पुरानो जाहेरवाला',
            'pratiwadi_name' => '[]',
            'mudda_bibran' => 'पुरानो विवरण',
            'user_name' => 'test',
        ]);

        $new = FiscalYear::create([
            'name' => '2083/084',
            'start_year' => 2083,
            'end_year' => 2084,
            'is_current' => false,
        ]);

        FiscalYearContext::set($new->id);

        $this->assertDatabaseHas('mudda_dartas', ['id' => $oldRecord->id, 'fiscal_year_id' => $old->id]);
        $this->assertCount(0, MuddaDarta::query()->get());
        $this->assertCount(1, MuddaDarta::forFiscalYear($old->id)->get());
    }

    public function test_archived_year_cannot_be_modified_through_mutating_routes(): void
    {
        $user = \App\Models\User::factory()->create();

        $old = FiscalYear::create([
            'name' => '2082/083',
            'start_year' => 2082,
            'end_year' => 2083,
            'is_current' => false,
            'is_closed' => true,
        ]);

        FiscalYearContext::set($old->id);

        $response = $this->actingAs($user)->post(route('mudda_darta.store'), [
            'mudda_name' => 'Should not save',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('mudda_dartas', ['mudda_name' => 'Should not save']);
    }
}