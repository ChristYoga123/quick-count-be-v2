@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>

        @forelse ($survey as $answer)
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <h5 class="card-title">{!! $answer->SurveyQuestion->question !!}</h5>
                    <h6 class="card-subtitle text-muted mb-3">{{ $answer->answer }}</h6>
                </div>
            </div>
        @empty
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pemberitahuan</h5>
                    <h6 class="card-subtitle text-muted mb-3">Belum ada data yang masuk<h6>
                </div>
            </div>
        @endforelse
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
@endsection
