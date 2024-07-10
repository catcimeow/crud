<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'fname',
        'lname',
        'contact',
        'address',
        'barangay',
        'city',
        'landmark',
        'id_user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}