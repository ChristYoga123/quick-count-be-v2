<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPilpar extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Pilpar()
    {
        return $this->belongsTo(Pilpar::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
