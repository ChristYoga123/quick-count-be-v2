<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function SurveyCategory()
    {
        return $this->belongsTo(SurveyCategory::class);
    }
}
