@extends('layout.main')

@section('title', 'Quản lý người dùng')

@section('content')
    <div class="page-header">
        <h1>Quản lý người dùng</h1>
        <div class="page-actions">
            <a href="{{ route('admin.users.create') }}" class="btn-primary"><i class="fas fa-plus-circle"></i> Thêm người dùng mới</a>
        </div>
    </div>

    {{-- Thông báo đã được hiển thị trong layout.main, không cần hiển thị lại ở đây --}}

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên đăng nhập</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->ma_nguoi_dung }}</td>
                        <td>{{ $user->ten_dang_nhap }}</td>
                        <td>{{ $user->ho_ten }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->vai_tro == 'quan_tri')
                                <span class="badge badge-primary">Quản trị viên</span>
                            @elseif($user->vai_tro == 'giao_vien')
                                <span class="badge badge-success">Giáo viên</span>
                            @else
                                <span class="badge badge-info">Học sinh</span>
                            @endif
                        </td>
                        <td class="action-buttons">
                            <a href="{{ route('admin.users.edit', $user->ma_nguoi_dung) }}" class="btn-icon btn-edit" title="Sửa"><i class="fas fa-edit"></i></a>
                            
                            @if($user->ma_nguoi_dung != auth()->user()->ma_nguoi_dung)
                                <form action="{{ route('admin.users.destroy', $user->ma_nguoi_dung) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')" title="Xóa"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
    }
    .badge-primary {
        background-color: #6366f1;
        color: white;
    }
    .badge-success {
        background-color: #10b981;
        color: white;
    }
    .badge-info {
        background-color: #60a5fa;
        color: white;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table th, .data-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #4b5563;
    }
    .data-table tr:hover {
        background-color: #f9fafb;
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-edit {
        background-color: #f59e0b;
        color: white;
    }
    .btn-edit:hover {
        background-color: #d97706;
        transform: translateY(-2px);
    }
    .btn-delete {
        background-color: #ef4444;
        color: white;
    }
    .btn-delete:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }
    .inline-form {
        display: inline;
    }
</style>
@endsection