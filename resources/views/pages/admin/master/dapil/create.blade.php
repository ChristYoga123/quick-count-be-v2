@extends('layouts.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.master.dapil.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="indexDapil">Index Dapil</label>
                                <input type="text" class="form-control @error('index') is-invalid @enderror"
                                    id="indexDapil" name="index" placeholder="Masukkan index dapil" />
                                @error('index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select2Basic" class="form-label">Kelurahan</label>
                                <select id="select2Multiple"
                                    class="select2 form-select kelurahan @error('kelurahan') is-invalid @enderror" multiple
                                    name="kelurahan[]">
                                    @foreach ($kelurahans as $kelurahan)
                                        <option value="{{ $kelurahan->subdis_name }}">{{ $kelurahan->subdis_name }}</option>
                                    @endforeach
                                </select>
                                @error('kelurahan')
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
    <link href="{{ asset('select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('select2/dist/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });
    </script>
@endpush
