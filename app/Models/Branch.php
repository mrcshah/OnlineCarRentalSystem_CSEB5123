<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location', 'code', 'owner_id'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function staff()
    {
        return $this->belongsToMany(User::class, 'branch_user');
    }
}
