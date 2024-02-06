@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.master.partai.update', $partai->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="title">Nama Partai</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="title" name="nama" placeholder="Masukkan nama partai"
                                    value="{{ $partai->nama }}" />
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="title">Foto Partai</label>
                                @if ($partai->getFirstMediaUrl('partai'))
                                    <img src="{{ $partai->getFirstMediaUrl('partai') }}" class="img-preview d-block mb-3"
                                        style="width: 300px">
                                @else
                                    <img class="img-preview" style="width: 300px">
                                @endif
                                <input type="file"
                                    class="form-control img-input @error('fotoPartai') is-invalid @enderror" id="title"
                                    name="fotoPartai" onchange="previewImage()" />
                                @error('fotoPartai')
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
        function previewImage() {
            const img = document.querySelector('.img-input');
            const imgPreview = document.querySelector('.img-preview');

            imgPreview.style.display = 'block';

            const oFReader = new FileReader();
            oFReader.readAsDataURL(img.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
                imgPreview.style.marginBottom = '10px';
            };
        }
    </script>
@endpush
