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
        'opening_time',
        'closing_time',
        'maps_url',
        'status',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class)
            ->withPivot('status')
            ->withTimestamps();
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
}