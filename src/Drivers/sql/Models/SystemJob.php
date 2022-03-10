<?php

namespace Scpigo\SystemJob\Drivers\sql\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemJob extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'action',
        'action_params',
        'scheduled_at',
        'status'
    ];

    public function scopeAction($query, $action) {
        if (!is_null($action)) {
            return $query->whereIn('action', $action);
        }
    }

    public function scopeAttempt($query, $attempt) {
        if (!is_null($attempt)) {
            return $query->where('attempt', $attempt);
        }
    }

    public function scopeStatus($query, $status) {
        if (!is_null($status)) {
            return $query->whereIn('status', $status);
        }
    }
}
