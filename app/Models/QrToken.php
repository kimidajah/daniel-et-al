<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['token', 'created_by', 'expires_at'])]
class QrToken extends Model
{
    /**
     * Get the user (Piket) who created the QR token.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if the token is expired.
     */
    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at);
    }
}
