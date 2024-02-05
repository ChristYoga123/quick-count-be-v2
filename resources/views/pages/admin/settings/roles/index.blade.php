@extends('layouts.app')

@section('content')
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-semibold mb-4">Daftar {{ $title }}</h4>

            @can('Peran.web.create')
                <div class="mb-4" style="width: 15%">
                    <button data-bs-target="#addRoleModal" data-bs-toggle="modal"
                        class="btn btn-primary mb-2 text-nowrap add-new-role">
                        Tambah {{ $title }}
                    </button>
                </div>
            @endcan

            <!-- Permission Table -->
            <div class="card">

                <div class="card-datatable table-responsive">
                    {{ $dataTable->table(['class' => 'datatables table border-top']) }}
                </div>
            </div>
            <!--/ Permission Table -->

            <!-- Modal -->
            <!-- Add Permission Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-5">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <h3 class="role-title mb-2">Tambah Peran Baru</h3>
                                <p class="text-muted">Setel hak akses tiap peran</p>
                            </div>
                            <!-- Add role form -->
                            <form id="addRoleForm" class="row g-3" method="POST"
                                action="{{ route('admin.settings.roles.store') }}">
                                @csrf
                                <div class="col-12 mb-4">
                                    <label class="form-label" for="modalRoleName">Nama Peran</label>
                                    <input type="text" id="modalRoleName" name="addName"
                                        class="form-control @error('addName') is-invalid @enderror"
                                        placeholder="Masukkan nama peran" tabindex="-1" />
                                    @error('addName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <h5>Hak Akses</h5>
                                    @error('addPermissions')
                                        <p class="text-danger" style="margin-top: -15px">{{ $message }}</p>
                                    @enderror
                                    <!-- Permission table -->
                                    <div class="table-responsive">
                                        <table class="table table-flush-spacing">
                                            <tbody>
                                                <tr>
                                                    <td class="text-nowrap fw-semibold">
                                                        Administrator Access
                                                        <i class="ti ti-info-circle" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Allows a full access to the system"></i>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="selectAll" />
                                                            <label class="form-check-label" for="selectAll"> Select All
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @foreach ($modules as $module)
                                                    <tr>
                                                        <td class="text-nowrap fw-semibold">{{ ucfirst($module) }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Index"
                                                                        name="addPermissions[]"
                                                                        value="{{ $module }}.web.index" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Index">
                                                                        Index </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Show"
                                                                        name="addPermissions[]"
                                                                        value="{{ $module }}.web.show" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Show">
                                                                        Show </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Create"
                                                                        name="addPermissions[]"
                                                                        value="{{ $module }}.web.create" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Create">
                                                                        Create </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Store"
                                                                        name="addPermissions[]"
                                                                        value="{{ $module }}.web.store" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Store">
                                                                        Store </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Edit"
                                                                        name="addPermissions[]"
                                                                        value="{{ $module }}.web.edit" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Edit">
                                                                        Edit </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Update"
                                                                        name="addPermissions[]"
                                                                        value="{{ $module }}.web.update" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Update">
                                                                        Update </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Delete"
                                                                        name="addPermissions[]"
                                                                        value="{{ $module }}.web.delete" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Delete">
                                                                        Delete </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Permission table -->
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Simpan</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        Kembali
                                    </button>
                                </div>
                            </form>
                            <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Add Permission Modal -->

            <!-- Edit Permission Modal -->
            <div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-5">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <h3 class="role-title mb-2">Edit Peran</h3>
                                <p class="text-muted">Setel hak akses tiap peran</p>
                            </div>
                            <!-- Add role form -->
                            <form id="editRoleForm" class="row g-3" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col-12 mb-4">
                                    <label class="form-label" for="modalRoleName">Nama Peran</label>
                                    <input type="text" id="modalRoleName" name="editName"
                                        class="form-control @error('editName') is-invalid @enderror"
                                        placeholder="Masukkan nama peran" tabindex="-1" />
                                    @error('editName')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <h5>Hak Akses</h5>
                                    @error('editPermissions')
                                        <p class="text-danger" style="margin-top: -15px">{{ $message }}</p>
                                    @enderror
                                    <!-- Permission table -->
                                    <div class="table-responsive">
                                        <table class="table table-flush-spacing">
                                            <tbody>
                                                <tr>
                                                    <td class="text-nowrap fw-semibold">
                                                        Administrator Access
                                                        <i class="ti ti-info-circle" data-bs-toggle="tooltip"
                                                            data-bs-placement="top"
                                                            title="Allows a full access to the system"></i>
                                                    </td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="editSelectAll" />
                                                            <label class="form-check-label" for="editSelectAll"> Select
                                                                All
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @foreach ($modules as $module)
                                                    <tr>
                                                        <td class="text-nowrap fw-semibold">{{ ucfirst($module) }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Index"
                                                                        name="editPermissions[]"
                                                                        value="{{ $module }}.web.index" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Index">
                                                                        Index </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Show"
                                                                        name="editPermissions[]"
                                                                        value="{{ $module }}.web.show" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Show">
                                                                        Show </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Create"
                                                                        name="editPermissions[]"
                                                                        value="{{ $module }}.web.create" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Create">
                                                                        Create </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Store"
                                                                        name="editPermissions[]"
                                                                        value="{{ $module }}.web.store" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Store">
                                                                        Store </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Edit"
                                                                        name="editPermissions[]"
                                                                        value="{{ $module }}.web.edit" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Edit">
                                                                        Edit </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Update"
                                                                        name="editPermissions[]"
                                                                        value="{{ $module }}.web.update" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Update">
                                                                        Update </label>
                                                                </div>
                                                                <div class="form-check me-3 me-lg-5">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="{{ $module }}Delete"
                                                                        name="editPermissions[]"
                                                                        value="{{ $module }}.web.delete" />
                                                                    <label class="form-check-label"
                                                                        for="{{ $module }}Delete">
                                                                        Delete </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Permission table -->
                                </div>
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-primary me-sm-3 me-1">Simpan</button>
                                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        Kembali
                                    </button>
                                </div>
                            </form>
                            <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Edit Permission Modal -->

            <!-- /Modal -->
        </div>
        <!-- / Content -->
        <div class="content-backdrop fade"></div>
    </div>
@endsection

@push('scripts')
    <!-- Page JS -->
    {{ $dataTable->scripts() }}
    @if (session('success'))
        <script>
            Swal.fire(
                'Success!',
                '{{ session('success') }}',
                'success'
            )
        </script>
    @elseif($errors->any())
        <script>
            Swal.fire(
                'Error!',
                'Terdapat kesalahan saat menambahkan peran baru. Mohon periksa kembali form yang diisi',
                'error'
            )
        </script>
    @elseif(session('error'))
        <script>
            Swal.fire(
                'Error!',
                '{{ session('error') }}',
                'error'
            )
        </script>
    @endif
    <script>
        $('#selectAll').on('change', function() {
            if ($(this).is(':checked')) {
                $('input[type=checkbox]').prop('checked', true);
            } else {
                $('input[type=checkbox]').prop('checked', false);
            }
        });

        $('#editSelectAll').on('change', function() {
            if ($(this).is(':checked')) {
                $('input[type=checkbox]').prop('checked', true);
            } else {
                $('input[type=checkbox]').prop('checked', false);
            }
        });

        $('#editRoleModal').on('hidden.bs.modal', function() {
            $('#editRoleForm').trigger('reset');
            $('#addRoleForm').trigger('reset');
        });

        $('#addRoleModal').on('hidden.bs.modal', function() {
            $('#addRoleForm').trigger('reset');
        });

        function editRole(id) {
            $('form#editRoleForm').attr('action', `/admin/settings/roles/${id}`);
            $.ajax({
                url: `/admin/settings/roles/${id}`,
                type: 'GET',
                success: function(response) {
                    $("input[name='editName']").val(response.name);
                    response.permissions.forEach(permission => {
                        $(`input[value='${permission.name}']`).prop('checked', true);
                    });
                }
            })
        }

        function deleteRole(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak akan dapat mengembalikan data yang telah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(`form#deleteRole${id}`).submit();
                }
            })
        }
    </script>
@endpush
