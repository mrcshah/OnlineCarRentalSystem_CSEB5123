<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'start_date',
        'end_date',
        'total_price',
        'status',
    ];

    public function cars()
    {
        return $this->belongsToMany(Car::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}