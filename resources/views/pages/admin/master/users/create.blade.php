@extends('layouts.app')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-semibold mb-4">{{ $title }}</h4>
        <!-- Basic Layout -->
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.master.petugas.singleStore') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="title">Nama Petugas</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="title" name="name" placeholder="Masukkan nama petugas" />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="title">Email Petugas</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="title" name="email" placeholder="Masukkan email petugas" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="title">Telepon Petugas</label>
                                <input type="number" class="form-control @error('phone_number') is-invalid @enderror"
                                    id="title" name="phone_number" placeholder="Masukkan telepon petugas" />
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="title">Password Petugas</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="title" name="password" placeholder="Masukkan password petugas" />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Peran Petugas</label>
                                <select name="roles" class="form-control">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
