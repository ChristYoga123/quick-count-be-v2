<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\SurveyQuestionDataTable;
use App\Http\Controllers\Controller;
use App\Models\SurveyCategory;
use App\Models\SurveyQuestion;
use App\Models\SurveyTitle;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurveyQuestionController extends Controller
{
    public $permission = "Survey.web";
    /**
     * Display a listing of the resource.
     */
    public function index(SurveyQuestionDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.surveys.pertanyaan.index', [
            'title' => 'Pertanyaan Survey',
            'surveyTitles' => SurveyTitle::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        return view('pages.admin.surveys.pertanyaan.create', [
            'title' => 'Tambah Pertanyaan Survey',
            'categories' => SurveyCategory::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        if ($request->type === 'radio') {
            foreach ($request->options as $option) {
                if (empty($option)) {
                    return redirect()->back()->with('error', 'Opsi tidak boleh kosong');
                }
            }
        }
        $request->validate([
            'index' => 'required|string',
            'survey_category_id' => 'required|exists:survey_categories,id',
            'type' => 'required|in:text,radio,textarea,date,number,time',
            'question' => 'required|string',
            'options' => 'array',
            'options.*' => 'nullable|string'
        ]);
        DB::beginTransaction();
        try {
            $options = [];
            if (!empty($request->options)) {
                foreach ($request->options as $option) {
                    if (!empty($option)) {
                        // create object to store option like this {option: L, skip: null}
                        $options[] = ['option' => $option, 'skip' => null];
                    }
                }
            }
            if ($request->type != 'radio') {
                SurveyQuestion::create([
                    'index' => $request->index,
                    'survey_category_id' => $request->survey_category_id,
                    'question' => $request->question,
                    'type' => $request->type,
                    'options' => null
                ]);
            } else {
                SurveyQuestion::create([
                    'index' => $request->index,
                    'survey_category_id' => $request->survey_category_id,
                    'question' => $request->question,
                    'type' => $request->type,
                    'options' => json_encode($options)
                ]);
            }
            DB::commit();
            return redirect()->route('admin.survey.pertanyaan.index')->with('success', 'Pertanyaan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.survey.pertanyaan.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyQuestion $pertanyaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyQuestion $pertanyaan)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.surveys.pertanyaan.edit', [
            'title' => 'Edit Pertanyaan Survey',
            'pertanyaan' => $pertanyaan,
            'categories' => SurveyCategory::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyQuestion $pertanyaan)
    {
        $this->confirmAuthorization('update');
        if ($request->type === 'radio') {
            if ($request->options == null) {
                return redirect()->back()->with('error', 'Opsi tidak boleh kosong');
            }
            foreach ($request->options as $option) {
                if (empty($option)) {
                    return redirect()->back()->with('error', 'Opsi tidak boleh kosong');
                }
            }
        }
        $request->validate([
            'survey_category_id' => 'required|exists:survey_categories,id',
            'type' => 'required|in:text,radio,textarea,date,number,time',
            'question' => 'required|string',
            'options' => 'array',
            'options.*' => 'nullable|string'
        ]);
        DB::beginTransaction();
        try {
            $options = [];
            if (!empty($request->options)) {
                foreach ($request->options as $option) {
                    if (!empty($option)) {
                        // create object to store option like this {option: L, skip: null}
                        $options[] = ['option' => $option, 'skip' => null];
                    }
                }
            }
            if ($request->type != 'radio') {
                $pertanyaan->update([
                    'index' => $request->index,
                    'survey_category_id' => $request->survey_category_id,
                    'question' => $request->question,
                    'type' => $request->type,
                    'options' => null
                ]);
            } else {
                $pertanyaan->update([
                    'index' => $request->index,
                    'survey_category_id' => $request->survey_category_id,
                    'question' => $request->question,
                    'type' => $request->type,
                    'options' => json_encode($options)
                ]);
            }
            DB::commit();
            return redirect()->route('admin.survey.pertanyaan.index')->with('success', 'Pertanyaan berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.survey.pertanyaan.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyQuestion $pertanyaan)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $pertanyaan->delete();
            DB::commit();
            return redirect()->route('admin.survey.pertanyaan.index')->with('success', 'Pertanyaan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.survey.pertanyaan.index')->with('error', $e->getMessage());
        }
    }

    private function confirmAuthorization($operation)
    {
        if (!auth()->user()->can($this->permission . '.' . $operation)) {
            throw AuthorizationException('Anda tidak memiliki akses untuk halaman ini.');
        }
    }
}
