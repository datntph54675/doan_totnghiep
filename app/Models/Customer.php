<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use Notifiable;

    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    protected $fillable = [
        'fullname',
        'gender',
        'birthdate',
        'phone',
        'email',
        'id_number',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'customer_id', 'customer_id');
    }

    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
}
