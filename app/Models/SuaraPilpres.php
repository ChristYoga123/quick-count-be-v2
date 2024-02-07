<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuaraPilpres extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Pilpres()
    {
        return $this->belongsTo(Pilpres::class);
    }

    public function Capres()
    {
        return $this->belongsTo(Capres::class);
    }
}
