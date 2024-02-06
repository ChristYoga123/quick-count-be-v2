<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\SurveyTitleDataTable;
use App\Http\Controllers\Controller;
use App\Models\SurveyTitle;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyTitleController extends Controller
{
    public $permission = "Survey.web";
    /**
     * Display a listing of the resource.
     */
    public function index(SurveyTitleDataTable $dataTable)
    {
        $this->confirmAuthorization('index');
        return $dataTable->render('pages.admin.surveys.judul.index', [
            'title' => 'Judul Survey'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->confirmAuthorization('create');
        return view('pages.admin.surveys.judul.create', [
            'title' => 'Tambah Judul Survey'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->confirmAuthorization('store');
        $request->validate([
            'judul' => 'required|unique:survey_titles,judul',
            'deskripsi' => 'required'
        ]);
        DB::beginTransaction();
        try {
            SurveyTitle::create([
                'judul' => ucwords($request->judul),
                'deskripsi' => $request->deskripsi
            ]);
            DB::commit();
            return redirect()->route('admin.survey.judul.index')->with('success', 'Judul survey berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SurveyTitle $judul)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SurveyTitle $judul)
    {
        $this->confirmAuthorization('edit');
        return view('pages.admin.surveys.judul.edit', [
            'title' => 'Edit Judul Survey',
            'surveyTitle' => $judul
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SurveyTitle $judul)
    {
        $this->confirmAuthorization('update');
        $request->validate([
            'judul' => 'required|unique:survey_titles,judul,' . $judul->id,
            'deskripsi' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $judul->update([
                'judul' => ucwords($request->judul),
                'deskripsi' => $request->deskripsi
            ]);
            DB::commit();
            return redirect()->route('admin.survey.judul.index')->with('success', 'Judul survey berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SurveyTitle $judul)
    {
        $this->confirmAuthorization('destroy');
        DB::beginTransaction();
        try {
            $judul->delete();
            DB::commit();
            return redirect()->route('admin.survey.judul.index')->with('success', 'Judul survey berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function confirmAuthorization($operation)
    {
        if (!Auth::user()->can($this->permission . '.' . $operation)) {
            throw new AuthorizationException('Anda tidak memiliki akses untuk halaman ini.');
        }
    }
}
