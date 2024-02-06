@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">{{ $title }}</h4>

            @can('Survey.web.create')
                <div class="mb-4" style="width: 15%">
                    <a href="{{ route('admin.survey.pertanyaan.create') }}" class="btn btn-primary mb-2 text-nowrap">
                        Tambah {{ $title }}
                    </a>
                </div>
            @endcan

            <!-- Permission Table -->
            <div class="card">
                <div class="mt-3 mr-3" style="width: 100%;">
                    <div class="d-flex gap-2 justify-content-end">
                        <label class="form-label my-auto">Judul Survey : </label>
                        <select class=" form-select" name="survey_title_id" style="width: 18%; margin-right: 20px"
                            onchange="changeTitle()">
                            <option value="">Pilih Judul</option>
                            @foreach ($surveyTitles as $surveyTitle)
                                <option value="{{ $surveyTitle->id }}">{{ $surveyTitle->judul }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    {{ $dataTable->table(['class' => 'datatables table border-top']) }}
                </div>
            </div>
            <!--/ SurveyCategory Table -->
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
                'Terdapat kesalahan saat menambahkan peran baru. Mohon periksa kembali form yang diisi',
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
        const table = $('#surveyquestion-table');
        table.on('preXhr.dt', function(e, settings, data) {
            data.survey_title_id = $('select[name="survey_title_id"]').val();
        });

        function changeTitle() {
            table.DataTable().ajax.reload();
            return false;
        }

        function deletePertanyaan(id) {
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
                    $(`form#deletePertanyaan${id}`).submit();
                }
            })
        }
    </script>
@endpush
