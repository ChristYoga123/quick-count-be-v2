@extends('layouts.app')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>

        {{-- Charts --}}
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <div>
                        <h5 class="card-title mb-0">Total Suara Pemilihan Partai Keseluruhan</h5>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <canvas id="pilpar" class="chartjs" data-height="500"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <div>
                        <h5 class="card-title mb-0">Total Suara Pemilihan Partai Berdasarkan Dapil</h5>
                    </div>
                </div>
                <div class="card-body-dapil pt-2">
                    <div class="d-flex justify-content-center gap-3">
                        <select name="dapil_id" class="form-select select-primary" style="width: 30%">
                            <option value="">Pilih Dapil</option>
                            @foreach ($dapils as $dapil)
                                <option value="{{ $dapil->id }}">{{ $dapil->index }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="changeSuaraPilpar()">Filter</button>
                        <button type="button" class="btn btn-danger d-none" onclick="destroySuaraPilpar()">Destroy</button>
                    </div>
                    <canvas id="pilpar-dapil" class="chartjs" data-height="500"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
    <div class="content-backdrop fade"></div>
    </div>
@endsection
@push('scripts')
    <script>
        const ctx = document.getElementById('pilpar').getContext('2d');
        const data = {!! json_encode($data) !!}
        const pilpar = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(d => d.nama),
                datasets: [{
                    label: 'Total Suara',
                    data: data.map(d => d.jumlah_suara),
                    borderWidth: 1,
                    backgroundColor: data.map(d => d.color)
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: '#fff',
                        anchor: 'end',
                        align: 'start',
                        offset: 4,
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels],
        });

        function changeSuaraPilpar() {
            const id = $('select[name=dapil_id]').val();
            const ctx = document.getElementById('pilpar-dapil').getContext('2d');
            const pilPar = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: "",
                    datasets: [{
                        label: 'Total Suara',
                        data: [],
                        borderWidth: 1,
                    }]
                },
                options: {
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            anchor: 'end',
                            align: 'start',
                            offset: 4,
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels],
            });
            $.ajax({
                url: `/admin/real-count/pilpar/${id}`,
                method: 'GET',
                success: function(data) {
                    $('select[name=dapil_id]').addClass('d-none');
                    $('.btn-danger').removeClass('d-none');
                    $('.btn-primary').addClass('d-none');
                    pilPar.data.labels = data.map(d => d.nama);
                    pilPar.data.datasets[0].data = data.map(d => d.jumlah_suara);
                    pilPar.data.datasets[0].backgroundColor = data.map(d => d.color);
                    pilPar.update();
                }
            })
        }

        function destroySuaraPilpar() {
            $('#pilpar-dapil').remove();
            // destroy chart
            $('.btn-danger').addClass('d-none');
            $('.btn-primary').removeClass('d-none');
            $('select[name=dapil_id]').removeClass('d-none');
            $('.card-body-dapil').empty();
            $('.card-body-dapil').append(
                `
                <div class="d-flex justify-content-center gap-3">
                        <select name="dapil_id" class="form-select select-primary" style="width: 30%">
                            <option value="">Pilih Dapil</option>
                            @foreach ($dapils as $dapil)
                                <option value="{{ $dapil->id }}">{{ $dapil->index }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="changeSuaraPilpar()">Filter</button>
                        <button type="button" class="btn btn-danger d-none"
                            onclick="destroySuaraPilpar()">Destroy</button>
                    </div>
                    <canvas id="pilpar-dapil" class="chartjs" data-height="500"></canvas>
                `
            );
        }
    </script>
@endpush
