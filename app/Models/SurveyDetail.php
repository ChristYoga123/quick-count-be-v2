<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function surveyor()
    {
        return $this->belongsTo(User::class, 'surveyor_id');
    }

    public function surveyAnswers()
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
