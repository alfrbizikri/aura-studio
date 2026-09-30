<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'requires_photographer',
        'requires_room',
        'is_on_location',
        'status',
    ];

    protected $casts = [
        'requires_photographer' => 'boolean',
        'requires_room' => 'boolean',
        'is_on_location' => 'boolean',
    ];

    public function branches()
    {
        return $this->belongsToMany(Branch::class)
            ->withPivot('status')
            ->withTimestamps();
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}