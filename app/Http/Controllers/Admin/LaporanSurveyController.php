<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurveyAnswer;
use App\Models\SurveyDetail;
use App\Models\SurveyTitle;
use Illuminate\Http\Request;

class LaporanSurveyController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SurveyTitle::all();
            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('admin.survey.laporan.show', $data->id) . '" class="btn btn-sm"><i class="ti ti-eye"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.admin.surveys.laporan.index', [
            'title' => 'Laporan Survey'
        ]);
    }

    public function show(Request $request, SurveyTitle $surveyTitle)
    {
        if ($request->ajax()) {
            $data = SurveyDetail::with('surveyor')->where('survey_title_id', $surveyTitle->id)->get();
            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . route('admin.survey.laporan.showAnswer', $data->id) . '" class="btn btn-sm"><i class="ti ti-eye"></i></a>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.admin.surveys.laporan.show', [
            'title' => 'Detail Survey',
            'survey' => $surveyTitle
        ]);
    }

    public function showAnswer(SurveyDetail $surveyDetail)
    {
        $surveyAnswer = SurveyAnswer::with('SurveyQuestion')->where('survey_detail_id', $surveyDetail->id)->get();
        return view('pages.admin.surveys.laporan.show-answer', [
            'title' => 'Detail Jawaban Survey',
            'survey' => $surveyAnswer
        ]);
    }
}
