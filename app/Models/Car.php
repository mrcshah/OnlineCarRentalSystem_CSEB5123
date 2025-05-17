<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Branch;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'brand',
        'model',
        'type',
        'transmission',
        'plate_number',
        'price_per_day',
        'is_available',
        'car_image',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function bookings()
    {
        return $this->belongsToMany(Booking::class)->withTimestamps();
    }
}
