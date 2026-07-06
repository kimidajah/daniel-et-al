<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_guru_can_only_access_guru_dashboard(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);

        // Check Guru dashboard
        $response = $this->actingAs($guru)->get('/dashboard/guru');
        $response->assertStatus(200);

        // Check forbidden dashboards
        $this->actingAs($guru)->get('/dashboard/piket')->assertStatus(403);
        $this->actingAs($guru)->get('/dashboard/tu')->assertStatus(403);
        $this->actingAs($guru)->get('/dashboard/kepala')->assertStatus(403);
    }

    public function test_piket_can_only_access_piket_dashboard(): void
    {
        $piket = User::factory()->create(['role' => 'piket']);

        // Check Piket dashboard
        $response = $this->actingAs($piket)->get('/dashboard/piket');
        $response->assertStatus(200);

        // Check forbidden dashboards
        $this->actingAs($piket)->get('/dashboard/guru')->assertStatus(403);
        $this->actingAs($piket)->get('/dashboard/tu')->assertStatus(403);
        $this->actingAs($piket)->get('/dashboard/kepala')->assertStatus(403);
    }

    public function test_tu_can_only_access_tu_dashboard(): void
    {
        $tu = User::factory()->create(['role' => 'tu']);

        // Check TU dashboard
        $response = $this->actingAs($tu)->get('/dashboard/tu');
        $response->assertStatus(200);

        // Check forbidden dashboards
        $this->actingAs($tu)->get('/dashboard/guru')->assertStatus(403);
        $this->actingAs($tu)->get('/dashboard/piket')->assertStatus(403);
        $this->actingAs($tu)->get('/dashboard/kepala')->assertStatus(403);
    }

    public function test_kepala_sekolah_can_only_access_kepala_dashboard(): void
    {
        $kepsek = User::factory()->create(['role' => 'kepala_sekolah']);

        // Check Kepala Sekolah dashboard
        $response = $this->actingAs($kepsek)->get('/dashboard/kepala');
        $response->assertStatus(200);

        // Check forbidden dashboards
        $this->actingAs($kepsek)->get('/dashboard/guru')->assertStatus(403);
        $this->actingAs($kepsek)->get('/dashboard/piket')->assertStatus(403);
        $this->actingAs($kepsek)->get('/dashboard/tu')->assertStatus(403);
    }
}
