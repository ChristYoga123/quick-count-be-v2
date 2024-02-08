@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>

        @forelse ($laporanPilpres as $laporan)
            <div class="card h-100 mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $laporan->name }}</h5>
                    {{-- <h6 class="card-subtitle text-muted mb-3">Tps: {{ $laporan->LaporanPilpres->Pilpres->tps }} Kelurahan:
                        {{ $laporan->Pilpres->kelurahan }}</h6> --}}
                    <ul>
                        @foreach ($laporan->LaporanPilpres as $data)
                            <li>
                                <p class="text-muted">[TPS: {{ $data->Pilpres->tps }} Kelurahan:
                                    {{ $data->Pilpres->kelurahan }}]: <span
                                        style="color: black; font-weight: 500">{{ $data->laporan }}</span>
                                </p>
                            </li>
                        @endforeach
                    </ul>
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
