<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPilleg extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Pilleg()
    {
        return $this->belongsTo(Pilleg::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
