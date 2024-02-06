@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.survey.kategori.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Survey</label>
                                <select id="title" class=" form-select @error('survey_title_id') is-invalid  @enderror"
                                    data-allow-clear="true" name="survey_title_id">
                                    @foreach ($surveyTitles as $surveyTitle)
                                        <option value="{{ $surveyTitle->id }}">{{ $surveyTitle->judul }}</option>
                                    @endforeach
                                </select>
                                @error('survey_title_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="categoryName">Nama Kategori</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="categoryName" name="nama" placeholder="Masukkan kategori survey" />
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
