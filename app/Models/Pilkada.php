<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilkada extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dapil()
    {
        return $this->belongsTo(Dapil::class);
    }
}
