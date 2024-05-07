<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'activity_log';

      protected $fillable = [
        'log_name',
        'description',
         'subject_type',
        'causer_type',
        'subject_id',
         'properties',
        'event',
         'batch_uuid'
    ];
}
