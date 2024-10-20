<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SurveyAnswer;
use App\Models\SurveyDetail;
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

    public function show($id)
    {
        $surveyQuestion = SurveyQuestion::with('SurveyCategory')->whereHas('SurveyCategory', function ($query) use ($id) {
            $query->where('survey_title_id', $id);
        })->get();
        if ($surveyQuestion->isEmpty()) {
            return ResponseFormatter::error('Data Survey Tidak Ditemukan', 404);
        }
        return ResponseFormatter::success($surveyQuestion, 'Data Survey Berhasil Diambil');
    }

    public function answer(Request $request)
    {
        $request->validate([
            'survey_title_id' => 'required|exists:survey_titles,id',
            'kecamatan' => 'required',
            'kelurahan' => 'required',
            'nama_responden' => 'required',
            'answers' => 'required|array',
            'answers.*.survey_question_id' => 'required|exists:survey_questions,id',
            'answers.*.answer' => 'nullable',
        ], [
            'survey_title_id.required' => 'Judul Survey tidak boleh kosong',
            'kecamatan.required' => 'Kecamatan tidak boleh kosong',
            'kelurahan.required' => 'Kelurahan tidak boleh kosong',
            'nama_responden.required' => 'Nama Responden tidak boleh kosong',
            'answers.required' => 'Jawaban tidak boleh kosong',
            'answers.array' => 'Jawaban harus berupa array',
            'answers.*.survey_question_id.required' => 'Survey Question ID tidak boleh kosong',
            'answers.*.survey_question_id.exists' => 'Survey Question ID tidak ditemukan',
            'answers.*.answer.nullable' => 'Jawaban harus berupa string',
        ]);
        DB::beginTransaction();
        try {
            $surveyDetail = SurveyDetail::create([
                'survey_title_id' => $request->survey_title_id,
                'surveyor_id' => auth('api')->user()->id,
                'kecamatan' => $request->kecamatan,
                'kelurahan' => $request->kelurahan,
                'nama_responden' => $request->nama_responden,
            ]);
            foreach ($request->answers as $answer) {
                $surveyAnswer = SurveyAnswer::create([
                    'survey_detail_id' => $surveyDetail->id,
                    'survey_question_id' => $answer['survey_question_id'],
                    'answer' => $answer['answer'],
                ]);
            }
            DB::commit();
            return ResponseFormatter::success(null, 'Data Survey Berhasil Disimpan');
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
