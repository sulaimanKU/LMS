<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OnlineClass extends Model
{
    protected $table = 'online_classes';
    protected $fillable = [
      'module_id',
    'teacher_id',
    'title',
    'description',
    'meeting_link',
    'meeting_id',
    'meeting_password',
    'class_date',
    'start_time',
    'duration',
    'status'
    ];


    public function module()
    {
        return $this->belongsTo(Courses::class,'module_id');
    }
    public function teacher(){
        return $this->belongsTo(Teacher::class,'teacher_id');
    }

    public function attendances(){
        return $this->hasMany(Attendance::class,'online_class_id');
    }

    /**
     * Auto-expire any live classes whose scheduled end time (class_date + start_time + duration)
     * has passed. Call this at the top of any controller that displays class status.
     */
    public static function autoExpireLive(): void
    {
        static::where('status', 'live')
            ->whereRaw(
                "TIMESTAMP(class_date, start_time) + INTERVAL COALESCE(duration, 60) MINUTE < NOW()"
            )
            ->update(['status' => 'finished']);
    }
}
