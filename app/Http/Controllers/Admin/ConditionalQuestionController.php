<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ConditionalQuestionDataTable;
use App\Http\Controllers\Controller;
use App\Models\SurveyQuestion;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConditionalQuestionController extends Controller
{
    public $permission = "Survey.web";
    /**
     * Display a listing of the resource.
     */
    public function index(ConditionalQuestionDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.surveys.kondisi.index', [
            'title' => 'Kondisi Pertanyaan'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyQuestion $perkondisian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyQuestion $perkondisian)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.surveys.kondisi.edit', [
            'title' => 'Edit Kondisi Pertanyaan',
            'perkondisian' => $perkondisian,
            'questions' => SurveyQuestion::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyQuestion $perkondisian)
    {
        $this->confirmAuthorization('update');
        foreach ($request->redirect as $redirect) {
            if ($redirect == $perkondisian->id) {
                return redirect()->back()->with('error', 'Pertanyaan tidak boleh mengarah ke dirinya sendiri');
            }
        }
        $request->validate([
            'option' => 'required|array',
            'redirect' => 'required|array',
            'redirect.*' => 'nullable|integer|exists:survey_questions,id'
        ]);
        DB::beginTransaction();
        try {
            $options = [];
            foreach ($request->option as $key => $value) {
                $options[] = [
                    'option' => $value,
                    // parse to integer
                    'skip' => (int) $request->redirect[$key]
                ];
            }
            $perkondisian->update([
                'options' => json_encode($options)
            ]);
            DB::commit();
            return redirect()->route('admin.survey.perkondisian.index')->with('success', 'Perkondisian Radio berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyQuestion $perkondisian)
    {
        //
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . "." . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini');
        }
    }
}
