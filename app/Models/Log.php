<?php

namespace App\Models;

use App\Models\Callback;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    use HasFactory;
    protected $fillable = [
        'callback_id',
        'description',
        'created_by',
    ];

    public function callback(): BelongsTo
    {
        return $this->belongsTo(Callback::class);
    }
}
