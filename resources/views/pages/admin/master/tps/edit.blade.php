@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.master.tps.update', $tps->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="indexTPS">Index TPS</label>
                                <input type="text" class="form-control @error('index') is-invalid @enderror"
                                    id="indexTPS" name="index" placeholder="Masukkan index tps"
                                    value="{{ $tps->index }}" />
                                @error('index')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select2Basic" class="form-label">Kabupaten</label>
                                <select id="select2Basic"
                                    class="select2 form-select @error('kecamatan') is-invalid @enderror"
                                    data-allow-clear="true" name="kecamatan" onchange="districtChange()">
                                    @foreach ($kecamatans as $kecamatan)
                                        <option value="{{ $kecamatan->dis_name }}"
                                            {{ $tps->kecamatan == $kecamatan->dis_name ? 'selected' : '' }}>
                                            {{ $kecamatan->dis_name }}</option>
                                    @endforeach
                                </select>
                                @error('kecamatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select2Basic" class="form-label">Kabupaten</label>
                                <select id="select2Basic"
                                    class="select2 form-select @error('kelurahan') is-invalid @enderror"
                                    data-allow-clear="true" name="kelurahan">
                                    @foreach ($kelurahans as $kelurahan)
                                        <option value="{{ $kelurahan->subdis_name }}"
                                            {{ $tps->kelurahan == $kelurahan->subdis_name ? 'selected' : '' }}>
                                            {{ $kelurahan->subdis_name }}</option>
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
    <script>
        function provinceChange() {
            let name = $('select[name="provinsi"]').val();
            $.ajax({
                url: `/admin/master/kabupaten/${name}`,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    $('select[name="kabupaten"]').empty();
                    $('select[name="kabupaten"]').append(
                        '<option value="" selected disabled>Pilih Kabupaten</option>');
                    $.each(response, function(key, value) {
                        $('select[name="kabupaten"]').append(
                            `<option value="${value.city_name}">${value.city_name}</option>`);
                    });
                    $('select[name="kabupaten"]').prop('disabled', false);
                }
            })
        }

        function cityChange() {
            let name = $('select[name="kabupaten"]').val();
            $.ajax({
                url: `/admin/master/kecamatan/${name}`,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    $('select[name="kecamatan"]').empty();
                    $('select[name="kecamatan"]').append(
                        '<option value="" selected disabled>Pilih Kecamatan</option>');
                    $.each(response, function(key, value) {
                        $('select[name="kecamatan"]').append(
                            `<option value="${value.dis_name}">${value.dis_name}</option>`);
                    });
                    $('select[name="kecamatan"]').prop('disabled', false);
                }
            })
        }

        function districtChange() {
            let name = $('select[name="kecamatan"]').val();
            $.ajax({
                url: `/admin/master/kelurahan/${name}`,
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    $('select[name="kelurahan"]').empty();
                    $('select[name="kelurahan"]').append(
                        '<option value="" selected disabled>Pilih Kelurahan</option>');
                    $.each(response, function(key, value) {
                        $('select[name="kelurahan"]').append(
                            `<option value="${value.subdis_name}">${value.subdis_name}</option>`);
                    });
                    $('select[name="kelurahan"]').prop('disabled', false);
                }
            })
        }
    </script>
@endpush
