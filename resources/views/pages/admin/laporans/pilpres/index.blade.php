@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">{{ $title }}</h4>

            <!-- Permission Table -->
            <div class="card">

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
@endpush
