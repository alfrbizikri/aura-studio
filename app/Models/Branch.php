<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'open_time',
        'close_time',
        'is_active',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function photographers()
    {
        return $this->hasMany(Photographer::class);
    }

    public function studioRooms()
    {
        return $this->hasMany(StudioRoom::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}