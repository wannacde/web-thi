@extends('layout.main')

@section('title', 'Danh sách môn học')

@section('content')
    <div class="subjects-header">
        <h1>Danh sách môn học</h1>
        @if(Auth::user()->vai_tro == 'quan_tri')
            <a href="{{ route('subjects.create') }}" class="btn-primary">Thêm môn học mới</a>
        @endif
    </div>

    @if(count($subjects) > 0)
        <div class="subjects-list">
            @foreach($subjects as $subject)
                <div class="subject-item">
                    <div class="subject-content">
                        <h2>{{ $subject->ten_mon_hoc }}</h2>
                        <p>{{ $subject->mo_ta ?? 'Không có mô tả' }}</p>
                        <p><strong>Số chương:</strong> {{ $subject->chuong_count }}</p>
                    </div>
                    <div class="subject-actions">
                        <a href="{{ route('subjects.show', $subject->slug) }}" class="btn-primary">Chi tiết</a>
                        <a href="{{ route('subjects.chapters', $subject->slug) }}" class="btn-primary">Quản lý chương</a>
                        
                        @if(Auth::user()->vai_tro == 'quan_tri')
                            <a href="{{ route('subjects.edit', $subject->slug) }}" class="btn-primary">Sửa</a>
                            
                            <form action="{{ route('subjects.destroy', $subject->slug) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-primary" onclick="return confirm('Bạn có chắc chắn muốn xóa môn học này?')">Xóa</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Không có môn học nào.</p>
    @endif
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
    .subjects-header h1:before {
        content: '\f02d';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .subjects-list li {
        background: #fff;
        border-left: 4px solid #3490dc;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(52,144,220,0.08);
        padding: 1rem 1.5rem;
        border-radius: 8px;
    }
    .btn-primary i {
        margin-right: 0.5rem;
    }
</style>
@endsection
