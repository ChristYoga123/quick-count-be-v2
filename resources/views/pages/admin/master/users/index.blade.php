@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">Daftar Petugas</h4>

            @can('Petugas.web.create')
                <div class="d-flex gap-4">
                    <div class="mb-4 me-5" style="width: 15%">
                        <button type="button" class="btn btn-success mb-2 text-nowrap" data-bs-toggle="modal"
                            data-bs-target="#modalUser">
                            Import Petugas By Excel
                        </button>
                    </div>

                    <div class="mb-4" style="width: 15%">
                        <a href="{{ route('admin.master.petugas.create') }}" class="btn btn-primary mb-2 text-nowrap">
                            Tambah {{ $title }}
                        </a>
                    </div>
                </div>
            @endcan

            <!-- Permission Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    {{ $dataTable->table(['class' => 'datatables table border-top']) }}
                </div>
            </div>
            <!--/ SurveyCategory Table -->

            {{-- Modal User --}}
            <div class="modal fade" id="modalUser" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">Tambah Petugas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.master.petugas.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="nameWithTitle" class="form-label">File Excel <span
                                                class="text-danger">('.xlsx')</span></label>
                                        <input type="file" id="nameWithTitle"
                                            class="form-control @error('excelFile') is-invalid @enderror"
                                            name="excelFile" />
                                        @error('excelFile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
    @if (session('success'))
        <script>
            Swal.fire(
                'Success!',
                '{{ session('success') }}',
                'success'
            )
        </script>
    @elseif($errors->any())
        <script>
            Swal.fire(
                'Error!',
                'Terdapat kesalahan saat menambahkan petugas baru. Mohon periksa kembali form yang diisi',
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
    <script>
        function deletePetugas(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda tidak akan dapat mengembalikan data yang telah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`form#deletePetugas${id}`).submit();
                }
            })
        }
    </script>
@endpush
