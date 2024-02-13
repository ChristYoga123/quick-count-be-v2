@extends('layouts.app')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header header-elements">
                    <div>
                        <h5 class="card-title mb-0">Total Suara Legislatif Keseluruhan</h5>
                    </div>
                </div>
                <div class="card-body-pilleg pt-2">
                    <div class="d-flex justify-content-center gap-3">
                        <select name="partai_id" class="form-select select-primary" style="width: 30%">
                            <option value="">Pilih Partai</option>
                            @foreach ($partais as $partai)
                                <option value="{{ $partai->id }}">{{ $partai->nama }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="changeSuaraLegislatif()">Filter</button>
                        <button type="button" class="btn btn-danger d-none"
                            onclick="destroySuaraLegislatif()">Destroy</button>
                    </div>
                    <canvas id="pilleg" class="chartjs" data-height="500"></canvas>
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
        function changeSuaraLegislatif() {
            const id = $('select[name=partai_id]').val();
            const ctx = document.getElementById('pilleg').getContext('2d');
            const pilleg = new Chart(ctx, {
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
            });
            $.ajax({
                url: `/admin/real-count/pileg/${id}`,
                method: 'GET',
                success: function(data) {
                    $('select[name=partai_id]').addClass('d-none');
                    $('.btn-danger').removeClass('d-none');
                    $('.btn-primary').addClass('d-none');
                    pilleg.data.labels = data.map(d => d.nama);
                    pilleg.data.datasets[0].data = data.map(d => d.jumlah_suara);
                    pilleg.update();
                }
            })
        }

        function destroySuaraLegislatif() {
            $('pilleg').remove();
            // destroy chart
            $('.btn-danger').addClass('d-none');
            $('.btn-primary').removeClass('d-none');
            $('select[name=partai_id]').removeClass('d-none');
            $('.card-body-pilleg').empty();
            $('.card-body-pilleg').append(
                `
                <div class="d-flex justify-content-center gap-3">
                        <select name="partai_id" class="form-select select-primary" style="width: 30%">
                            <option value="">Pilih Partai</option>
                            @foreach ($partais as $partai)
                                <option value="{{ $partai->id }}">{{ $partai->nama }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="changeSuaraLegislatif()">Filter</button>
                        <button type="button" class="btn btn-danger d-none"
                            onclick="destroySuaraLegislatif()">Destroy</button>
                    </div>
                    <canvas id="pilleg" class="chartjs" data-height="500"></canvas>
                `
            );
        }
    </script>
@endpush
