<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            $data = SurveyDetail::all();
            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $button = '<a href="' . "#" . '" class="btn btn-sm"><i class="ti ti-eye"></i></a>';
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
}
