@extends('layouts.app')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.survey.pertanyaan.update', $pertanyaan->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="category" class="form-label">Judul Pertanyaan</label>
                                <select id="category"
                                    class=" form-select @error('survey_category_id') is-invalid  @enderror"
                                    data-allow-clear="true" name="survey_category_id">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $category->id == $pertanyaan->category_id ? 'selected' : '' }}>
                                            {{ $category->nama }}</option>
                                    @endforeach
                                </select>
                                @error('survey_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="index" for="categoryName">Indeks Pertanyaan</label>
                                <input type="text" class="form-control @error('index') is-invalid @enderror"
                                    id="index" name="index" placeholder="Masukkan indeks pertanyaan"
                                    value="{{ $pertanyaan->index }}" />
                                @error('index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe Pertanyaan</label>
                                <select id="type" class="select2 form-select @error('type') is-invalid @enderror"
                                    data-allow-clear="true" name="type" onchange="changeType()">
                                    <option value="text" {{ $pertanyaan->type == 'text' ? 'selected' : '' }}>Text</option>
                                    <option value="radio" {{ $pertanyaan->type == 'radio' ? 'selected' : '' }}>Radio
                                    </option>
                                    <option value="textarea" {{ $pertanyaan->type == 'textarea' ? 'selected' : '' }}>
                                        Textarea
                                    </option>
                                    <option value="number" {{ $pertanyaan->type == 'number' ? 'selected' : '' }}>Number
                                    </option>
                                    <option value="date" {{ $pertanyaan->type == 'date' ? 'selected' : '' }}>Date
                                    </option>
                                    <option value="time" {{ $pertanyaan->type == 'time' ? 'selected' : '' }}>Time
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('option')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="question" for="categoryName">Nama Pertanyaan</label>
                                <textarea name="question" id="question">{{ $pertanyaan->question }}</textarea>
                                @error('question')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($pertanyaan->type == 'radio')
                                <div class="mb-3 form-repeat">
                                    <label for="" class="form-label">Opsi</label>
                                    <div data-repeater-list="group-a" style="margin-top: -5px">
                                        <div data-repeater-item id="output">
                                            @foreach (json_decode($pertanyaan->options) as $option)
                                                <div class="row">
                                                    <div class="mb-3 col-12 mb-0">
                                                        <label class="form-label" for="option"></label>
                                                        <input type="text" name="options[]" id="option"
                                                            class="form-control" placeholder="Opsi"
                                                            value="{{ $option->option }}" />
                                                    </div>
                                                </div>
                                            @endforeach
                                            <div id="output">
                                                <div class="row">
                                                    <div class="mb-3 col-12 mb-0">
                                                        <label class="form-label" for="option"></label>
                                                        <input type="text" id="option" class="form-control"
                                                            placeholder="Opsi" name="options[]" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lainnya d-flex flex-row gap-1 mb-3">
                                        <input type="checkbox" class="form-check-input" id="other"
                                            onclick="otherOptions()">
                                        <label class="form-check-label" for="other"
                                            onclick="otherOptions()">Lainnya</label>
                                    </div>
                                    <div class="row">
                                        <div class="mb-0">
                                            <button onclick="addOptions()" type="button" class="btn btn-primary"
                                                data-repeater-create>
                                                <i class="ti ti-plus me-1"></i>
                                                <span class="align-middle">Tambah</span>
                                            </button>
                                            <button onclick="deleteOptions()" type="button" class="btn btn-danger"
                                                data-repeater-create>
                                                <i class="ti ti-trash me-1"></i>
                                                <span class="align-middle">Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mb-3 d-none form-repeat">
                                    <label for="" class="form-label">Opsi</label>
                                    <div data-repeater-list="group-a" style="margin-top: 5px">
                                        <div data-repeater-item id="output">
                                            <div class="row">
                                                <div class="mb-3 col-12 mb-0">
                                                    <label class="form-label" for="option"></label>
                                                    <input type="text" id="option" class="form-control"
                                                        placeholder="Opsi" name="options[]" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="lainnya d-flex flex-row gap-1 mb-3">
                                        <input type="checkbox" class="form-check-input" id="other"
                                            onclick="otherOptions()">
                                        <label class="form-check-label" for="other"
                                            onclick="otherOptions()">Lainnya</label>
                                    </div>
                                    <div class="row">
                                        <div class="mb-0">
                                            <button onclick="addOptions()" type="button" class="btn btn-primary"
                                                data-repeater-create>
                                                <i class="ti ti-plus me-1"></i>
                                                <span class="align-middle">Tambah</span>
                                            </button>
                                            <button onclick="deleteOptions()" type="button" class="btn btn-danger"
                                                data-repeater-create>
                                                <i class="ti ti-trash me-1"></i>
                                                <span class="align-middle">Hapus</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    @if (session('error'))
        <script>
            Swal.fire(
                'Error!',
                '{{ session('error') }}',
                'error'
            )
        </script>
    @endif
    <script>
        CKEDITOR.replace('question');

        $('#type').on('change', function() {
            if ($(this).val() == 'radio') {
                $('.form-repeat').removeClass('d-none');
            } else {
                $('.form-repeat').addClass('d-none');
            }
        });

        function addOptions() {
            let child = `
                <div class="row">
                    <div class="mb-3 col-12 mb-0">
                        <label class="form-label" for="option"></label>
                        <input type="text" id="option" class="form-control"
                            placeholder="Opsi" name="options[]" />
                    </div>
                </div>
            `
            $('#output').append(child);
        }

        function otherOptions() {
            if ($('#other').is(':checked')) {
                // ambil elemen terakhir id output
                let last = $('#output').children().last();
                last.find('input').val('Lainnya');
            } else {
                let last = $('#output').children().last();
                last.find('input').val('');
            }
        }

        function changeType() {
            if ($('#type').val() == 'radio') {
                $('.form-repeat').removeClass('d-none');
            } else {
                $('.form-repeat').addClass('d-none');
            }
        }

        function deleteOptions() {
            $('#question-options').children().last().remove();
            $('#output').children().last().remove();
        }
    </script>
@endpush
