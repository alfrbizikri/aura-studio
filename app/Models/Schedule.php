<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'service_id',
        'photographer_id',
        'studio_room_id',
        'schedule_date',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'schedule_date' => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    public function studioRoom()
    {
        return $this->belongsTo(StudioRoom::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_schedule')
            ->withTimestamps();
    }
}