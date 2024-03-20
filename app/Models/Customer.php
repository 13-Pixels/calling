<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'destination',
        'enquiry_date',
        'last_contact',
    ];
    public function callback()
    {
        return $this->hasMany(Callback::class);
    }
}
