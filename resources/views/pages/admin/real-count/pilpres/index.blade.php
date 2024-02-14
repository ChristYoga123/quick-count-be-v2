@extends('layouts.app')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-2">{{ $title }}</h4>
        <div class="mb-4 d-flex justify-content-start" style="width: 100%">
            <a href="{{ route('real-count.pilpres.export') }}" class="btn btn-success">Export Laporan Real Count
                Presiden</a>
        </div>
        {{-- Charts --}}
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <div>
                        <h5 class="card-title mb-0">Total Suara Pilpres Keseluruhan</h5>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <canvas id="pilpres" class="chartjs" data-height="500"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <div>
                        <h5 class="card-title mb-0">Total Suara Pilpres Berdasarkan Dapil</h5>
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
                        <button type="button" class="btn btn-primary" onclick="changeSuaraPilpres()">Filter</button>
                        <button type="button" class="btn btn-danger d-none"
                            onclick="destroySuaraPilpres()">Destroy</button>
                    </div>
                    <canvas id="pilpres-dapil" class="chartjs" data-height="500"></canvas>
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
        const ctx = document.getElementById('pilpres').getContext('2d');
        const data = {!! json_encode($data) !!}
        const pilpres = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(d => d.nama_paslon),
                datasets: [{
                    label: 'Total Suara',
                    data: data.map(d => d.jumlah_suara),
                    borderWidth: 1,
                    backgroundColor: data.map(d => d.color)
                }]
            },
            plugins: [ChartDataLabels],
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
            }
        });

        function changeSuaraPilpres() {
            const id = $('select[name=dapil_id]').val();
            const ctx = document.getElementById('pilpres-dapil').getContext('2d');
            const pilpresDapil = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: "",
                    datasets: [{
                        label: 'Total Suara',
                        data: [],
                        borderWidth: 1
                    }]
                },
                plugins: [ChartDataLabels],
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
                }
            });
            $.ajax({
                url: `/admin/real-count/pilpres/${id}`,
                method: 'GET',
                success: function(data) {
                    $('select[name=dapil_id]').addClass('d-none');
                    $('.btn-danger').removeClass('d-none');
                    $('.btn-primary').addClass('d-none');
                    pilpresDapil.data.labels = data.map(d => d.nama_paslon);
                    pilpresDapil.data.datasets[0].data = data.map(d => d.jumlah_suara);
                    pilpresDapil.data.datasets[0].backgroundColor = data.map(d => d.color);
                    pilpresDapil.update();
                }
            })
        }

        function destroySuaraPilpres() {
            $('#pilpres-dapil').remove();
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
                        <button type="button" class="btn btn-primary" onclick="changeSuaraPilpres()">Filter</button>
                        <button type="button" class="btn btn-danger d-none"
                            onclick="destroySuaraPilpres()">Destroy</button>
                    </div>
                    <canvas id="pilpres-dapil" class="chartjs" data-height="500"></canvas>
                `
            );
        }
    </script>
@endpush
