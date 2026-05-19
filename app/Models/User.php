<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'municipality',
        'province',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function bookingSchedules()
    {
        return $this->hasMany(BookingSchedule::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function trainingRegistrations()
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    public function productDevelopments()
    {
        return $this->hasMany(ProductDevelopment::class);
    }
}
