<!-- resources/views/subjects/edit.blade.php -->
@extends('layout.main')

@section('title', 'Chỉnh sửa môn học')

@section('content')
    <h1>Chỉnh sửa môn học</h1>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('subjects.update', $subject->ma_mon_hoc) }}">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="ten_mon_hoc">Tên môn học</label>
            <input type="text" id="ten_mon_hoc" name="ten_mon_hoc" value="{{ old('ten_mon_hoc', $subject->ten_mon_hoc) }}" required>
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô tả</label>
            <textarea id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta', $subject->mo_ta) }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Cập nhật</button>
            <a href="{{ route('subjects.index') }}" class="btn-secondary">Hủy</a>
        </div>
    </form>
@endsection

@section('styles')
<style>
    .form-actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
    }
    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        text-decoration: none;
    }
</style>
@endsection
