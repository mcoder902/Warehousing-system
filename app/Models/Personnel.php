<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personnel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name', 'last_name', 'national_code',
        'personnel_code', 'department', 'phone', 'is_active'
    ];

    // اکسسور برای نام کامل
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // رابطه‌ها

    // کالاهایی که همین الان دست این شخص است
    public function currentItems()
    {
        return $this->hasMany(Item::class, 'current_personnel_id');
    }

    // کل تاریخچه کالاهایی که این شخص داشته
    public function assignmentHistory()
    {
        return $this->hasMany(Assignment::class);
    }
}
