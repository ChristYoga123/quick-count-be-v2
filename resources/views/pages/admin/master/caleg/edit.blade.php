@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.master.caleg.update', $caleg->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="title">No. Caleg</label>
                                <input type="number" class="form-control @error('no_urut') is-invalid @enderror"
                                    id="title" name="no_urut" placeholder="Masukkan urutan caleg"
                                    value="{{ $caleg->no_urut }}" />
                                @error('no_urut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="title">Nama Caleg</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="title" name="nama" placeholder="Masukkan nama caleg"
                                    value="{{ $caleg->nama }}" />
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select2Basic" class="form-label">Partai</label>
                                <select id="select2Basic" class="select2 form-select @error('partai') is-invalid @enderror"
                                    data-allow-clear="true" name="partai">
                                    <option value="" selected disabled>Pilih Partai</option>
                                    @foreach ($partais as $partai)
                                        <option value="{{ $partai->id }}"
                                            {{ $partai->id == $caleg->partai_id ? 'selected' : '' }}>{{ $partai->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('partai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="select2Basic" class="form-label">Dapil</label>
                                <select id="select2Basic" class="select2 form-select @error('dapil') is-invalid @enderror"
                                    data-allow-clear="true" name="dapil">
                                    <option value="" selected disabled>Pilih Dapil</option>
                                    @foreach ($dapils as $dapil)
                                        <option value="{{ $dapil->id }}"
                                            {{ $dapil->id == $caleg->dapil_id ? 'selected' : '' }}>
                                            {{ $dapil->index }}</option>
                                    @endforeach
                                </select>
                                @error('dapil')
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
