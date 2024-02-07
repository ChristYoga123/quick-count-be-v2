<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuaraPilleg extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Pilleg()
    {
        return $this->belongsTo(Pilleg::class);
    }

    public function Caleg()
    {
        return $this->belongsTo(Caleg::class);
    }
}
