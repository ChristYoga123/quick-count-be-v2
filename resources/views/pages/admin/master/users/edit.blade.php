@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ $title }}</h4>
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <div class="user-info text-center">
                                    <h4 class="mb-2">{{ $petuga->name }}</h4>
                                    <span
                                        class="badge bg-label-secondary mt-1">{{ $petuga->getRoleNames()->implode(',') }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 small text-uppercase text-muted">Details</p>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="fw-semibold me-1">Username:</span>
                                    <span>{{ $petuga->name }}</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Email:</span>
                                    <span>{{ $petuga->email }}</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Status:</span>
                                    <span class="badge bg-label-success">Active</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Peran:</span>
                                    <span>{{ $petuga->getRoleNames()->implode(',') }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
                <ul class="nav nav-pills flex-column flex-md-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i
                                class="ti ti-lock me-1 ti-xs"></i>Profil</a>
                    </li>
                </ul>
                <!--/ User Pills -->

                <!-- Change Password -->
                <div class="card mb-4">
                    <h5 class="card-header">Ubah Profil</h5>
                    <div class="card-body">
                        <form id="formChangePassword" method="POST"
                            action="{{ route('admin.master.petugas.update', $petuga->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3">
                                    <label class="form-label" for="title">Nama Petugas</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="title" name="name" placeholder="Masukkan nama petugas"
                                        value="{{ $petuga->name }}" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="title">Email Petugas</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="title" name="email" placeholder="Masukkan email petugas"
                                        value="{{ $petuga->email }}" />
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="title">Telepon Petugas</label>
                                    <input type="number" class="form-control @error('phone_number') is-invalid @enderror"
                                        id="title" name="phone_number" placeholder="Masukkan telepon petugas"
                                        value="{{ $petuga->UserCredential->phone_number ?? 0 }}" />
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                                    <label class="form-label" for="newPassword">Password baru</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control @error('password') is-invalid @enderror" type="password"
                                            id="newPassword" name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                                    <label class="form-label" for="confirmPassword">Konfirmasi password baru</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="password" name="password_confirmation"
                                            id="confirmPassword"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary me-2">Ubah Profil</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/ Change Password -->
            </div>
            <!--/ User Content -->
        </div>
        <!-- /Modals -->
    </div>
@endsection
