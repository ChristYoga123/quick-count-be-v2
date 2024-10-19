<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuaraPilkada extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pilkada()
    {
        return $this->belongsTo(Pilkada::class);
    }

    public function cakada()
    {
        return $this->belongsTo(Cakada::class);
    }
}
