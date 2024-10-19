@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">{{ $title }}</h4>

            @can('Cakada.web.create')
                <div class="mb-4" style="width: 15%">
                    <a href="{{ route('admin.master.cakada.create') }}" class="btn btn-primary mb-2 text-nowrap">
                        Tambah {{ $title }}
                    </a>
                </div>
            @endcan

            <!-- Permission Table -->
            <div class="card">

                <div class="card-datatable table-responsive">
                    <table class="datatables table border-top" id="cakada">
                        <thead>
                            <tr>
                                <th>No. Urut</th>
                                <th>Nama Paslon</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!--/ SurveyCategory Table -->
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
@endsection

@push('scripts')
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
        $('#cakada').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.master.cakada.index') }}',
            columns: [{
                    data: 'no_urut_paslon',
                    name: 'no_urut_paslon'
                },
                {
                    data: 'nama_paslon',
                    name: 'nama_paslon'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });

        function deleteCakada(id) {
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
                    $.ajax({
                        url: `{{ route('admin.master.cakada.destroy', '') }}/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            Swal.fire(
                                'Terhapus!',
                                'Data telah dihapus.',
                                'success'
                            )
                            $('#cakada').DataTable().ajax.reload();
                        },
                    })
                }
            })
        }
    </script>
@endpush
