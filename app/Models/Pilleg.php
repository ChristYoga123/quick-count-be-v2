<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilleg extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Dapil()
    {
        return $this->belongsTo(Dapil::class);
    }

    public function SuaraPillegs()
    {
        return $this->hasMany(SuaraPilleg::class);
    }
}
