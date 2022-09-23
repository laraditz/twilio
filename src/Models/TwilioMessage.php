<?php

namespace Laraditz\Twilio\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TwilioMessage extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'sid', 'account_sid', 'messaging_service_sid', 'direction', 'from', 'to', 'body', 'type', 'status', 'sent_at'];

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = $model->id ?? (string) Str::orderedUuid();
        });
    }
}
