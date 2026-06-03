<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $table = 'assignments';
    protected $fillable = [
        'teacher_id',
        'online_class_id',
        'title',
        'description',
        'file_path',
        'total_points',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];


    /** Get the teacher who created this assignment */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }


    public function onlineClass()
    {
        return $this->belongsTo(OnlineClass::class, 'online_class_id');
    }


    public function submissions()
    {
        return $this->hasMany(Submission::class,'assignment_id');
    }
}
