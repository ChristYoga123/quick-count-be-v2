<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function SurveyQuestion()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }

    public function SurveyDetail()
    {
        return $this->belongsTo(SurveyDetail::class);
    }
}
