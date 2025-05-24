<!-- resources/views/subjects/show.blade.php -->
@extends('layout.main')

@section('title', $subject->ten_mon_hoc)

@section('content')
    <div class="subject-header">
        <h1>{{ $subject->ten_mon_hoc }}</h1>
        <div class="subject-actions">
            <a href="{{ route('subjects.index') }}" class="btn-secondary">Quay lại</a>
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

    <div class="subject-details">
        <div class="detail-section">
            <h2>Thông tin môn học</h2>
            <div class="detail-item">
                <strong>Mô tả:</strong> {{ $subject->mo_ta ?? 'Không có mô tả' }}
            </div>
            <div class="detail-item">
                <strong>Số chương:</strong> {{ count($subject->chuong) }}
            </div>
        </div>
        
        <div class="detail-section">
            <h2>Danh sách chương</h2>
            
            @if(count($subject->chuong) > 0)
                <ul class="chapters-list">
                    @foreach($subject->chuong->sortBy('so_thu_tu') as $chapter)
                        <li class="chapter-item">
                            <div class="chapter-header">
                                <h3>{{ $chapter->so_thu_tu }}. {{ $chapter->ten_chuong }}</h3>
                                <span class="chapter-level 
                                    @if($chapter->muc_do == 'de') level-easy
                                    @elseif($chapter->muc_do == 'trung_binh') level-medium
                                    @else level-hard
                                    @endif">
                                    {{ $chapter->muc_do == 'de' ? 'Dễ' : ($chapter->muc_do == 'trung_binh' ? 'Trung bình' : 'Khó') }}
                                </span>
                            </div>
                            
                            <div class="chapter-content">
                                <p>{{ $chapter->mo_ta ?? 'Không có mô tả' }}</p>
                                <p><strong>Số câu hỏi:</strong> {{ $chapter->cauHoi->count() }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Không có chương nào.</p>
            @endif
        </div>
        
        <div class="detail-section">
            <h2>Bài thi thuộc môn học</h2>
            
            @if(count($subject->baiThi) > 0)
                <ul class="exams-list">
                    @foreach($subject->baiThi as $exam)
                        <li class="exam-item">
                            <div class="exam-content">
                                <h3>{{ $exam->ten_bai_thi }}</h3>
                                <div class="exam-details">
                                    <p><strong>Số câu hỏi:</strong> {{ $exam->tong_so_cau }}</p>
                                    <p><strong>Thời gian:</strong> {{ $exam->thoi_gian }} phút</p>
                                </div>
                            </div>
                            <div class="exam-actions">
                                <a href="{{ route('exams.show', $exam->slug) }}" class="btn-primary">Chi tiết</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Không có bài thi nào.</p>
            @endif
        </div>
    </div>
@endsection

@section('styles')
<style>
    .subject-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .subject-actions {
        display: flex;
        gap: 0.5rem;
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
    .subject-details {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    .detail-section {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
    }
    .detail-item {
        margin-bottom: 0.5rem;
    }
    .chapters-list, .exams-list {
        list-style-type: none;
        padding: 0;
    }
    .chapter-item, .exam-item {
        background-color: #fff;
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
    }
    .chapter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .chapter-level {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: bold;
        color: white;
    }
    .level-easy {
        background-color: #28a745;
    }
    .level-medium {
        background-color: #ffc107;
        color: #212529;
    }
    .level-hard {
        background-color: #dc3545;
    }
    .chapter-content {
        margin-top: 0.5rem;
    }
    .exam-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .exam-actions {
        display: flex;
        gap: 0.5rem;
    }
    .exam-details {
        margin-top: 0.5rem;
    }
    .exam-details p {
        margin: 0.25rem 0;
    }
    
    @media (min-width: 768px) {
        .subject-details {
            grid-template-columns: repeat(2, 1fr);
        }
        .detail-section:first-child {
            grid-column: 1 / -1;
        }
    }
</style>
@endsection
