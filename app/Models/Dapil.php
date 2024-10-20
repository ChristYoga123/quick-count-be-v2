<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dapil extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function Pilpres()
    {
        return $this->hasMany(Pilpres::class);
    }

    public function Pilpars()
    {
        return $this->hasMany(Pilpar::class);
    }

    public function Calegs()
    {
        return $this->hasMany(Caleg::class);
    }
}
