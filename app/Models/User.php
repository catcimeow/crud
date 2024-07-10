<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [  
        'email',
        'password',
        'profile_image_path',
        'is_admin',
        'is_activated'
    ];

    public function buyer()
    {
        return $this->hasOne(Buyer::class, 'id_user');
    }
}
