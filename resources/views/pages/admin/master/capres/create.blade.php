@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.master.capres.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="title">No. Capres</label>
                                <input type="number" class="form-control @error('no_urut_paslon') is-invalid @enderror"
                                    id="title" name="no_urut_paslon" placeholder="Masukkan urutan paslon" />
                                @error('no_urut_paslon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="title">Nama Capres</label>
                                <input type="text" class="form-control @error('nama_paslon') is-invalid @enderror"
                                    id="title" name="nama_paslon" placeholder="Masukkan nama paslon" />
                                @error('nama_paslon')
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
    @if ($errors->any())
        <script>
            Swal.fire(
                'Error!',
                'Ada kesalahan input data, cek kembali form!',
                'error'
            )
        </script>
    @elseif(session('error'))
        <script>
            Swal.fire(
                'Error!',
                '{{ session('error') }}',
                'error'
            )
        </script>
    @endif
@endpush
