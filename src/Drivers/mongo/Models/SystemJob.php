<?php

namespace Scpigo\SystemJob\Drivers\Mongo\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MongoDB\Operation\FindOneAndUpdate;

class SystemJob extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $connection = 'mongodb';

    protected $fillable = [
        'action',
        'action_params',
        'scheduled_at',
        'status'
    ];

    protected $dates = [
        'scheduled_at'
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

    public function nextid()
    {
        $this->id = self::getID();
    }

    public static function bootUseAutoIncrementID()
    {
        static::creating(function ($model) {
            $model->sequencial_id = self::getID($model->getTable());
        });
    }
    public function getCasts()
    {
        return $this->casts;
    }

    private static function getID()
    {
        $seq = DB::connection('mongodb')->getCollection('counters')->findOneAndUpdate(
            ['id' => 'id'],
            ['$inc' => ['seq' => 1]],
            ['new' => true, 'upsert' => true, 'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
        );
        return $seq->seq;
    }
}
