<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuaraPilpar extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Pilpar()
    {
        return $this->belongsTo(Pilpar::class);
    }

    public function Partai()
    {
        return $this->belongsTo(Partai::class);
    }
}
