<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'token'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
