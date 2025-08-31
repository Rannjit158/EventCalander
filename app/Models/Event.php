<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_at',
        'email',
        'reminder_minutes_before',
        'reminder_sent'
    ];

    protected $casts = [
        'event_at' => 'datetime',
        'reminder_sent' => 'boolean'
    ];
}
