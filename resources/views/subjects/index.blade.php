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
<style>
    .subjects-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .subjects-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .subject-item {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .subject-content {
        margin-bottom: 1rem;
    }
    .subject-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
</style>
@endsection
