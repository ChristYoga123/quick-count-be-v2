<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilpres extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Dapil()
    {
        return $this->belongsTo(Dapil::class);
    }

    public function SuaraPilpres()
    {
        return $this->hasMany(SuaraPilpres::class);
    }
}
