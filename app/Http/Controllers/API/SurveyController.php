<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SurveyAnswer;
use App\Models\SurveyQuestion;
use App\Models\SurveyTitle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('apiAuth');
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
        $request->validate([
            'answers' => 'required|array',
            'answers.*.survey_question_id' => 'required|exists:survey_questions,id',
            'answers.*.answer' => 'nullable',
        ]);
        DB::beginTransaction();
        try {
            $surveyAnswer = SurveyAnswer::insert($request->answers);
            DB::commit();
            return ResponseFormatter::success($surveyAnswer, 'Data Survey Berhasil Disimpan');
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
