<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SurveyQuestion;
use App\Models\SurveyTitle;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }
    public function index()
    {
        $judulSurvey = SurveyTitle::all();
        return ResponseFormatter::success($judulSurvey, 'Data Survey Berhasil Diambil');
    }

    public function show(SurveyTitle $surveyTitle)
    {
        $surveyQuestion = SurveyQuestion::with('SurveyCategory')->whereHas('SurveyCategory', function ($query) use ($surveyTitle) {
            $query->where('survey_title_id', $surveyTitle->id);
        })->get();
        if ($surveyQuestion->isEmpty()) {
            return ResponseFormatter::error('Data Survey Tidak Ditemukan', 404);
        }
        return ResponseFormatter::success($surveyQuestion, 'Data Survey Berhasil Diambil');
    }

    public function answer(Request $request)
    {
    }
}
