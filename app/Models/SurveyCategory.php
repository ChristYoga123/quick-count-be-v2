<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function SurveyTitle()
    {
        return $this->belongsTo(SurveyTitle::class);
    }
}
