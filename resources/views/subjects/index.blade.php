@extends('layout.main')

@section('title', 'Danh sách môn học')

@section('content')
    <div class="subjects-header">
        <h1><i class="fas fa-book"></i> Danh sách môn học</h1>
        @if(Auth::user()->vai_tro == 'quan_tri')
            <a href="{{ route('subjects.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm môn học</a>
        @endif
    </div>

    @if(count($subjects) > 0)
        <div class="subjects-list">
            @foreach($subjects as $subject)
                <div class="subject-item">
                    <div class="subject-icon">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="subject-content">
                        <h2>{{ $subject->ten_mon_hoc }}</h2>
                        <p>{{ $subject->mo_ta ?? 'Không có mô tả' }}</p>
                        <p><strong>Số chương:</strong> {{ $subject->chuong_count }}</p>
                    </div>
                    <div class="subject-actions">
                        <a href="{{ route('subjects.show', $subject->slug) }}" class="icon-btn" title="Chi tiết"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('subjects.chapters', $subject->slug) }}" class="icon-btn" title="Quản lý chương"><i class="fas fa-layer-group"></i></a>
                        @if(Auth::user()->vai_tro == 'quan_tri')
                            <a href="{{ route('subjects.edit', $subject->slug) }}" class="icon-btn" title="Sửa"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('subjects.destroy', $subject->slug) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc chắn muốn xóa môn học này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-btn" title="Xóa"><i class="fas fa-trash-alt"></i></button>
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
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
    }
    .subjects-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .subjects-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .subjects-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 2rem;
    }
    .subject-item {
        background: linear-gradient(120deg, #fff 60%, #e0eafc 100%);
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(52,144,220,0.10);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1.2rem;
        position: relative;
        min-height: 140px;
        transition: box-shadow 0.2s, transform 0.15s;
    }
    .subject-item:hover {
        box-shadow: 0 8px 32px rgba(52,144,220,0.18);
        transform: translateY(-4px) scale(1.02);
    }
    .subject-icon {
        font-size: 2.5rem;
        color: #3490dc;
        margin-right: 1rem;
        flex-shrink: 0;
        margin-top: 0.2rem;
    }
    .subject-content h2 {
        font-size: 1.3rem;
        font-weight: 600;
        color: #3490dc;
        margin-bottom: 0.3rem;
    }
    .subject-content p {
        margin: 0.2rem 0;
        color: #444;
    }
    .subject-actions {
        display: flex;
        flex-direction: row;
        gap: 0.5rem;
        margin-left: auto;
        align-items: center;
    }
    .icon-btn {
        background: #f1f5fa;
        border: none;
        color: #3490dc;
        border-radius: 50%;
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 2px 8px rgba(52,144,220,0.08);
        cursor: pointer;
        text-decoration: none;
    }
    .icon-btn:hover {
        background: #3490dc;
        color: #fff;
    }
    .inline-form {
        display: inline;
    }
    @media (max-width: 700px) {
        .subjects-header {
            flex-direction: column;
            gap: 1rem;
        }
        .subjects-list {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection
