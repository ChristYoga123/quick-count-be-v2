@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">{{ $title }}</h4>

            <!-- Permission Table -->
            <div class="card">

                <div class="card-datatable table-responsive">
                    <table class="datatables table border-top" id="survey-detail">
                        <thead>
                            <tr>
                                <th>Kelurahan</th>
                                <th>Surveyor</th>
                                <th>Nama Responden</th>
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
        $('#survey-detail').DataTable({
            processing: true,
            serverSide: true,
            ajax: `{{ route('admin.survey.laporan.show', $survey->id) }}`,
            columns: [{
                    data: 'kelurahan',
                    name: 'kelurahan'
                },
                {
                    data: 'surveyor.name',
                    name: 'surveyor.name'
                },
                {
                    data: 'nama_responden',
                    name: 'nama_responden'
                },
                {
                    data: 'action',
                    name: 'action'
                },
            ]
        });
    </script>
@endpush
