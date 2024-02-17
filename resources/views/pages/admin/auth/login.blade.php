@extends('layouts.guest')

@section('content')
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-4">
                <!-- Login -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center mb-4 mt-2">
                            <a href="index.html" class="app-brand-link gap-2">
                                <img src="{{ asset('logo.png') }}" class="img-fluid">
                            </a>
                        </div>
                        <!-- /Logo -->
                        <div class="text-center">
                            <h4 class="mb-1 pt-2">Selamat datang di Entry-RC 👋</h4>
                            <p class="mb-4">Dashboard Admin Data Real Count Pemilu {{ date('Y') }}
                            </p>
                        </div>

                        <form id="formAuthentication" class="mb-3" action="{{ route('admin.authenticate') }}"
                            method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Masukkan email anda" autofocus />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn d-grid text-white w-100" type="submit"
                                    style="background-color: #f04b4c">Masuk</button>
                            </div>

                            <div class="mb-3">
                                <a href="https://client.entryqc.id" class="btn  d-grid text-white w-100"
                                    style="background-color: #213159">Isi Data Melalui
                                    Web</a>
                            </div>

                            <div class="mb-3">
                                <a href="https://drive.google.com/file/d/1GCDMmExJzOF4UN58iqsCcNiQdecqzFEP/view?usp=sharing"
                                    class="btn  d-grid text-white w-100" style="background-color: #213159">Unduh
                                    Aplikasi</a>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            });
        </script>
    @endif
@endpush
