<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Submission extends Model
{
    protected $table = 'submissions';
    protected $fillable = [
        'assignment_id',
        'user_id',
        'file_path',
        'student_note',
        'teacher_comment',
        'grade',
        'status',
        'submitted_at',
    ];


    protected $casts = [
        'submitted_at' => 'datetime',
    ];


    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class,'assignment_id');
    }

    /** Get the student who made this submission */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
    {

        return $this->belongsTo(User::class, 'user_id');
    }
}
