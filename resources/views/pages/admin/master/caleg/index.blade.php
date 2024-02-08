@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">{{ $title }}</h4>

            @can('TPS.web.create')
                <div class="mb-4" style="width: 15%">
                    <button type="button" class="btn btn-success mb-2 text-nowrap" data-bs-toggle="modal"
                        data-bs-target="#modalCaleg">
                        Import Caleg By Excel
                    </button>
                </div>
            @endcan

            <!-- Permission Table -->
            <div class="card">
                <div class="mt-3 mr-3" style="width: 100%;">
                    <div class="d-flex gap-2 justify-content-end">
                        <select class=" form-select" name="partai_id" style="width: 18%;">
                            <option value="">Pilih Partai</option>
                            @foreach ($partais as $partai)
                                <option value="{{ $partai->id }}">{{ $partai->nama }}</option>
                            @endforeach
                        </select>

                        <select class=" form-select" name="dapil_id" style="width: 18%;">
                            <option value="">Pilih Dapil</option>
                            @foreach ($dapils as $dapil)
                                <option value="{{ $dapil->id }}">{{ $dapil->index }}</option>
                            @endforeach
                        </select>

                        <button type="button" onclick="changeCaleg()" class="btn btn-primary me-3">Filter</button>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    {{ $dataTable->table(['class' => 'datatables table border-top']) }}
                </div>
            </div>
            <!--/ SurveyCategory Table -->

            <div class="modal fade" id="modalCaleg" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalCenterTitle">Tambah Data Caleg</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.master.caleg.store') }}" method="post" enctype="multipart/form-data">
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
                `{{ $errors->first() }}`,
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
        const table = $('#caleg-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.partai_id = $('select[name="partai_id"]').val();
            data.dapil_id = $('select[name="dapil_id"]').val();
        });

        function changeCaleg() {
            table.DataTable().ajax.reload();
            return false;
        }

        function deleteCaleg(id) {
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
                    $(`form#deleteCaleg${id}`).submit();
                }
            })
        }
    </script>
@endpush
