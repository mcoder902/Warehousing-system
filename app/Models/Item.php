<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'model', 'serial_number', 'inventory_code',
        'description', 'status', 'current_personnel_id'
    ];

    // --- روابط ---

    public function currentPersonnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class, 'current_personnel_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function activeAssignment()
    {
        return $this->hasOne(Assignment::class)->whereNull('returned_at')->latestOfMany();
    }


    /**
     * تحویل کالا به پرسنل
     */
    public function assignTo(Personnel $personnel, User $admin, $notes = null)
    {
        if ($this->current_personnel_id) {
            throw new \Exception("این کالا در حال حاضر دست {$this->currentPersonnel->full_name} است.");
        }

        return DB::transaction(function () use ($personnel, $admin, $notes)
        {

            $this->assignments()->create([
                'personnel_id' => $personnel->id,
                'user_id' => $admin->id,
                'assigned_at' => now(),
                'notes' => $notes,
            ]);

            $this->update([
                'current_personnel_id' => $personnel->id,
                'status' => 'assigned'
            ]);
        });
    }

    /**
     * عودت کالا به انبار
     */
    public function returntoStorage($notes = null)
    {
        if (!$this->current_personnel_id) {
            throw new \Exception("این کالا دست کسی نیست که بخواهد برگردد.");
        }

        return DB::transaction(function () use ($notes) {
            // 1. پایان دادن به رکورد تاریخچه باز
            $this->activeAssignment()->update([
                'returned_at' => now(),
            ]);

            // 2. آزاد کردن کالا
            $this->update([
                'current_personnel_id' => null,
                'status' => 'available'
            ]);
        });
    }
}


