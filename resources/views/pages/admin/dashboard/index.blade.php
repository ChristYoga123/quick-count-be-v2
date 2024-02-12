@extends('layouts.app')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Statistics -->
            <div class="mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="card-title mb-0">Statistika</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-primary me-3 p-2">
                                        <i class="ti ti-users ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $capres }}</h5>
                                        <small>Presiden & Wakil Presiden</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-info me-3 p-2">
                                        <i class="ti ti-badge ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $partai }}</h5>
                                        <small>Partai</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                                        <i class="ti ti-user ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $caleg }}</h5>
                                        <small>Legislatif</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                                        <i class="ti ti-building ti-sm"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ $dapil }}</h5>
                                        <small>Dapil</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Statistics -->

            <div class="col-12 mb-4 d-flex justify-content-between">
                <div class="card col-6 me-2">
                    <div class="card-header header-elements">
                        <div>
                            <h5 class="card-title mb-0">Total Suara Pilpres Keseluruhan</h5>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <canvas id="pilpres" class="chartjs" data-height="500"></canvas>
                    </div>
                </div>
                <div class="card col-6">
                    <div class="card-header header-elements">
                        <div>
                            <h5 class="card-title mb-0">Total Suara Pilpres Keseluruhan</h5>
                        </div>
                    </div>
                    <div class="card-body pt-2">
                        <canvas id="pilpres-pie" class="chartjs"
                            style="height: 200px;
                        width: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header header-elements">
                        <div>
                            <h5 class="card-title mb-0">Total Suara Partai Keseluruhan</h5>
                        </div>
                    </div>
                    <div class="card-body pt-2 ">
                        <canvas id="pilpar" class="chartjs" data-height="500"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const ctx = document.getElementById('pilpres').getContext('2d');
        const data = {!! json_encode($realCountPresiden) !!}
        const pilpres = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(d => d.nama_paslon),
                datasets: [{
                    label: 'Total Suara',
                    data: data.map(d => d.total),
                    borderWidth: 1,
                    backgroundColor: data.map(d => d.color)
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        formatter: (value, context) => {
                            return value.toLocaleString();
                        }
                    }
                }
            },
            plugins: [ChartDataLabels],
        });

        const ctx1 = document.getElementById('pilpres-pie').getContext('2d');
        const data1 = {!! json_encode($realCountPresiden) !!}
        const pilpresPie = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: data1.map(d => d.nama_paslon),
                datasets: [{
                    label: 'Persentase Suara',
                    data: data1.map(d => d.persen),
                    borderWidth: 1,
                    backgroundColor: data1.map(d => d.color)
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        formatter: (value, context) => {
                            // add % symbol to the end of value
                            return value + ' %';
                        },
                        color: '#fff',
                    }
                },
                responsive: true,
            },
            plugins: [ChartDataLabels],
        });
        pilpresPie.options.responsive = true;
        pilpresPie.canvas.style.height = '300px';
        pilpresPie.canvas.style.width = '300px';

        const ctx2 = document.getElementById('pilpar').getContext('2d');
        const data2 = {!! json_encode($realCountPartai) !!}
        const pilpar = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: data2.map(d => d.nama),
                datasets: [{
                    label: 'Total Suara',
                    data: data2.map(d => d.total),
                    borderWidth: 1,
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        anchor: 'end',
                        align: 'end',
                        formatter: (value, context) => {
                            return value.toLocaleString();
                        }
                    }
                }
            },
            plugins: [ChartDataLabels],
        });
    </script>
@endpush
