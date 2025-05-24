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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
    }
    .subject-header h1:before {
        content: '\f02d';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .chapter-list li {
        background: #fff;
        border-left: 4px solid #3490dc;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(52,144,220,0.08);
        padding: 1rem 1.5rem;
        border-radius: 8px;
    }
    .exams-list .exam-item {
        background: #f8f9fa;
        border-left: 4px solid #6a82fb;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(106,130,251,0.08);
        padding: 1rem 1.5rem;
        border-radius: 8px;
    }
</style>
@endsection
