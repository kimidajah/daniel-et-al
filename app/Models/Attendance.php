<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'qr_token_id',
    'attendance_type',
    'date',
    'scan_time',
    'latitude',
    'longitude',
    'status',
    'validated_by',
    'validated_at',
    'notes',
])]
class Attendance extends Model
{
    /**
     * Get the teacher/user who submitted this attendance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the QR Token associated with this attendance scan.
     */
    public function qrToken(): BelongsTo
    {
        return $this->belongsTo(QrToken::class);
    }

    /**
     * Get the user (Piket) who validated this attendance.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
