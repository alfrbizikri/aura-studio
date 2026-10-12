<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'service_id',
        'name',
        'description',
        'status',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}