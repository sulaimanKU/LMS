<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSlip extends Model
{
    protected $fillable = ['registration_id', 'file_path', 'status', 'admin_notes'];

    public function registration() {
        return $this->belongsTo(Registration::class);
    }
}
