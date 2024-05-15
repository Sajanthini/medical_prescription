<?php

namespace App\Models;

use App\Models\User;
use App\Models\PrescriptionImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'note',
        'address',
        'delivery_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prescriptionImages()
    {
        return $this->hasMany(PrescriptionImage::class);
    }
}
