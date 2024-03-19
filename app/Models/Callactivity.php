<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Callactivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'user',
        'date',
        'type',
        'update',
    ];
    public function callback()
    {
        return $this->belongsTo(Callback::class);
    }
}
