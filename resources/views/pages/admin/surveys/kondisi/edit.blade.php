@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.survey.perkondisian.update', $perkondisian->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3 form-repeat">
                                <label for="" class="form-label">Opsi & Pengalihan</label>
                                <div data-repeater-list="group-a" style="margin-top: -20px">
                                    <div data-repeater-item id="output">
                                        @foreach (json_decode($perkondisian->options) as $option)
                                            <div class="d-flex gap-2">
                                                <div class="mb-3 mb-0" style="width: 70%">
                                                    <label class="form-label" for="option"></label>
                                                    <input type="text" name="option[]" id="option"
                                                        class="form-control" placeholder="Opsi"
                                                        value="{{ $option->option }}" readonly />
                                                </div>
                                                <span class="my-auto">Dialihkan ke</span>
                                                <div class="mb-3 mb-0" style="width: 20%">
                                                    <label class="form-label" for="option"></label>
                                                    <select id="title" class=" form-select form-select-lg"
                                                        name="redirect[]">
                                                        <option value="">Pilih Indeks Pertanyaan</option>
                                                        @foreach ($questions as $question)
                                                            @if ($perkondisian->id != $question->id)
                                                                <option value="{{ $question->id }}"
                                                                    {{ $option->skip == $question->id ? 'selected' : '' }}>
                                                                    {{ $question->index }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
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
@endpush
