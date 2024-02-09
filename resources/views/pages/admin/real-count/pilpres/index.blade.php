@extends('layouts.app')
@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>

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
                <div class="card-body pt-2">
                    <div class="d-flex justify-content-center gap-3">
                        <select name="dapil_id" class="form-select select-primary" style="width: 30%">
                            <option value="">Pilih Dapil</option>
                            @foreach ($dapils as $dapil)
                                <option value="{{ $dapil->id }}">{{ $dapil->index }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-primary" onclick="changeSuaraPilpres()">Filter</button>
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
                    borderWidth: 1
                }]
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
                }
            });
            $.ajax({
                url: `/admin/real-count/pilpres/${id}`,
                method: 'GET',
                beforeSend: function() {
                    // destroy canvas
                    pilpresDapil.destroy();
                },
                success: function(data) {
                    pilpresDapil.data.labels = data.map(d => d.nama_paslon);
                    pilpresDapil.data.datasets[0].data = data.map(d => d.jumlah_suara);
                    pilpresDapil.update();
                }
            })
        }
    </script>
@endpush
