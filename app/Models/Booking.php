<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'start_date',
        'end_date',
        'status',
        'total_price',
    ];
    public function cars(): BelongsToMany
    {
        return $this->belongsToMany(Car::class, 'booking_car');
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
