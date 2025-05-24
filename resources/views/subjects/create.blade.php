@extends('layout.main')

@section('title', 'Thêm môn học mới')

@section('content')
    <h1>Thêm môn học mới</h1>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('subjects.store') }}">
        @csrf
        
        <div class="form-group">
            <label for="ten_mon_hoc">Tên môn học</label>
            <input type="text" id="ten_mon_hoc" name="ten_mon_hoc" value="{{ old('ten_mon_hoc') }}" required>
        </div>

        <div class="form-group">
            <label for="mo_ta">Mô tả</label>
            <textarea id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta') }}</textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary"><i class="fas fa-plus"></i>Thêm môn học</button>
            <a href="{{ route('subjects.index') }}" class="btn-secondary">Hủy</a>
        </div>
    </form>
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
    }
    .form-group label:before {
        content: '\f044';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .btn-primary i {
        margin-right: 0.5rem;
    }
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
