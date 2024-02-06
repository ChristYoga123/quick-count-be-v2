@extends('layouts.app')
@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.master.dapil.update', $dapil->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="indexDapil">Index Dapil</label>
                                <input type="text" class="form-control @error('index') is-invalid @enderror"
                                    id="indexDapil" name="index" placeholder="Masukkan index tps"
                                    value="{{ $dapil->index }}" />
                                @error('index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select2Basic" class="form-label">Kecamatan</label>
                                <select id="select2Multiple"
                                    class="select2 form-select @error('kecamatan') is-invalid @enderror" multiple
                                    name="kecamatan[]">
                                    @foreach ($kecamatans as $kecamatan)
                                        <option value="{{ $kecamatan->subdis_name }}">{{ $kecamatan->subdis_name }}</option>
                                    @endforeach
                                </select>
                                @error('kecamatan')
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
    <script>
        $('#select2Multiple').select2({
            placeholder: 'Pilih Kecamatan',
            allowClear: true,
        });
    </script>
@endpush
