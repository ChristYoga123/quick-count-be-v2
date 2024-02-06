<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\SurveyCategoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\SurveyCategory;
use App\Models\SurveyTitle;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyCategoryController extends Controller
{
    public $permission = "Survey.web";
    /**
     * Display a listing of the resource.
     */
    public function index(SurveyCategoryDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.surveys.kategori.index', [
            'title' => 'Kategori Survey',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        return view('pages.admin.surveys.kategori.create', [
            'title' => 'Tambah Kategori Survey',
            'surveyTitles' => SurveyTitle::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'survey_title_id' => 'required|exists:survey_titles,id',
            'nama' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            SurveyCategory::create([
                'survey_title_id' => $request->survey_title_id,
                'nama' => ucwords($request->nama),
            ]);
            DB::commit();
            return redirect()->route('admin.survey.kategori.index')->with('success', 'Kategori survey berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.survey.kategori.index')->with('error', 'Kategori survey gagal ditambahkan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyCategory $kategori)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyCategory $kategori)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.surveys.kategori.edit', [
            'title' => 'Edit Kategori Survey',
            'kategori' => $kategori,
            'surveyTitles' => SurveyTitle::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyCategory $kategori)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'survey_title_id' => 'required|exists:survey_titles,id',
            'nama' => 'required|string',
        ]);
        DB::beginTransaction();
        try {
            $kategori->update([
                'survey_title_id' => $request->survey_title_id,
                'nama' => ucwords($request->nama),
            ]);
            DB::commit();
            return redirect()->route('admin.survey.kategori.index')->with('success', 'Kategori survey berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.survey.kategori.index')->with('error', 'Kategori survey gagal diubah.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyCategory $kategori)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $kategori->delete();
            DB::commit();
            return redirect()->route('admin.survey.kategori.index')->with('success', 'Kategori survey berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.survey.kategori.index')->with('error', $e->getMessage());
        }
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini.');
        }
    }
}
