<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\QrToken;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_teacher_profile(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        
        $profile = TeacherProfile::create([
            'user_id' => $user->id,
            'nip' => '12345678',
            'subject' => 'Fisika',
        ]);

        $this->assertNotNull($user->teacherProfile);
        $this->assertEquals('12345678', $user->teacherProfile->nip);
        $this->assertEquals('Fisika', $user->teacherProfile->subject);
        $this->assertEquals($user->id, $profile->user->id);
    }

    public function test_qr_token_belongs_to_creator(): void
    {
        $piket = User::factory()->create(['role' => 'piket']);

        $token = QrToken::create([
            'token' => 'random_secure_token_123',
            'created_by' => $piket->id,
            'expires_at' => now()->addSeconds(30),
        ]);

        $this->assertEquals($piket->id, $token->creator->id);
        $this->assertFalse($token->isExpired());
    }

    public function test_attendance_belongs_to_teacher_and_validator(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $piket = User::factory()->create(['role' => 'piket']);
        
        $token = QrToken::create([
            'token' => 'token_xyz',
            'created_by' => $piket->id,
            'expires_at' => now()->addSeconds(30),
        ]);

        $attendance = Attendance::create([
            'user_id' => $guru->id,
            'qr_token_id' => $token->id,
            'date' => now()->toDateString(),
            'scan_time' => now()->toTimeString(),
            'latitude' => -6.200000,
            'longitude' => 106.816666,
            'status' => 'pending',
        ]);

        $this->assertEquals($guru->id, $attendance->user->id);
        $this->assertEquals($token->id, $attendance->qrToken->id);
        $this->assertNull($attendance->validator);

        // Simulate validation
        $attendance->update([
            'status' => 'approved',
            'validated_by' => $piket->id,
            'validated_at' => now(),
        ]);

        $attendance->refresh();

        $this->assertEquals('approved', $attendance->status);
        $this->assertEquals($piket->id, $attendance->validator->id);
    }
}
