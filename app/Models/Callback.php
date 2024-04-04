<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Callback extends Model
{
    use HasFactory;
    protected $fillable = [
        'quote',
        'enquiry_date',
        'booking_date',
        'callback_date',
        'job_status',
        'location_id',
        'callback_status',
        'customer_id',
    ];
    public function activity()
    {
        return $this->hasMany(Callactivity::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
