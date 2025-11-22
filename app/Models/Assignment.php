<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assignment extends Model
{

    protected $fillable = [
        'item_id', 'personnel_id', 'user_id',
        'assigned_at', 'returned_at', 'notes'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    public function registrar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}