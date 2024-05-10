<?php

namespace App\Models;

use App\Models\Log;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Callback extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'quote',
        'enquiry_date',
        'booking_date',
        'callback_date',
        'job_status',
        'callback_status',
        'customer_email',
        'customer_phone',
        'customer_name',
        'pick_up',
        'drop_off',
        'via',
        'total',
        'discount',
        'close_date',
        'cancel_reason'
    ];
    public function activity()
    {
        return $this->hasMany(Callactivity::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
