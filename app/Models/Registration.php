<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
     protected $table = 'registrations';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'institution',
        'research_area',
        'selected_courses',
        'total_amount',
        'status',
        'approved_at',
        'status'
    ];

    protected $casts = [
        'selected_courses' => 'array',
    ];
    public function getSelectedCourseNamesAttribute()
{

    return Courses::whereIn('id', $this->selected_courses)->pluck('title');
}
public function slips() {
    return $this->hasMany(PaymentSlip::class);
}

}
