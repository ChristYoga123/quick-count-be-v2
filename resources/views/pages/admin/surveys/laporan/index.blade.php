@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">{{ $title }}</h4>

            <!-- Permission Table -->
            <div class="card">

                <div class="card-datatable table-responsive">
                    <table class="datatables table border-top" id="survey">
                        <thead>
                            <tr>
                                <th>Judul</th>
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
    <script>
        $('#survey').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.survey.laporan.index') }}',
            columns: [{
                    data: 'judul',
                    name: 'judul'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });
    </script>
@endpush
