@extends('dashboard.layouts.app')

@section('container')
<style>
    body {
        background-color: #f6ffde !important;
    }
    .card {
        border-radius: 14px;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .card-header {
        background: #ffffff;
        border-bottom: 2px solid #e8f5c8;
        padding: 16px 20px;
        border-radius: 14px 14px 0 0;
    }
    table thead {
        background: #e8f5c8;
    }
    table thead th {
        font-weight: 600;
        color: #3b4b2a;
    }
    .btn-primary {
        background-color: #5c7c2d;
        border: none;
    }
    .btn-primary:hover {
        background-color: #4a661f;
    }
    .btn-success {
        background-color: #4CAF50;
        border: none;
    }
    .btn-success:hover {
        background-color: #3c8d40;
    }
    .btn-danger {
        background-color: #d9534f;
    }
    .modal-content {
        border-radius: 14px;
    }
</style>

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="m-0">Data Users</h4>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
            Tambah User
        </button>
    </div>

    <div class="card-body">
        <table class="table table-hover" id="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role?->role_name }}</td>
                    <td>
                        <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#roleModal{{ $user->id }}">
                            Ganti Role
                        </button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah User --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role_id" class="form-control" required>
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="text-end">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
@foreach ($users as $user)
<div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah yakin menghapus user <strong>{{ $user->name }}</strong>?
                <p class="text-danger mt-2">Tindakan ini tidak dapat dibatalkan.</p>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Modal Ganti Role --}}
@foreach ($users as $user)
<div class="modal fade" id="roleModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="text-secondary text-center mb-2">Mengubah role akan mempengaruhi hak akses user.</p>
                <form action="{{ route('users.update-Role') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="mb-3">
                        <label class="form-label">Pilih Role</label>
                        <select name="role_id" class="form-control" required>
                            <option value="">Pilih role</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary w-100 mt-2">Ganti Role</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
