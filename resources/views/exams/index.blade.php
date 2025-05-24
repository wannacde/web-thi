@extends('layout.main')

@section('title', 'Danh sách bài thi')

@section('content')
    <div class="exam-header">
        <h1>Danh sách bài thi</h1>
        
        @if(Auth::user() && Auth::user()->vai_tro != 'hoc_sinh')
            <a href="{{ route('exams.create') }}" class="btn-primary">Tạo bài thi mới</a>
        @endif
    </div>

    @if(isset($exams) && count($exams) > 0)
        <div class="exam-filters">
            <input type="text" id="searchExam" placeholder="Tìm kiếm bài thi..." class="search-input">
            <select id="subjectFilter" class="filter-select">
                <option value="">Tất cả môn học</option>
                @php
                    $subjects = [];
                @endphp
                @foreach($exams as $exam)
                    @if(!in_array($exam->monHoc->ma_mon_hoc, $subjects))
                        <option value="{{ $exam->monHoc->ma_mon_hoc }}">{{ $exam->monHoc->ten_mon_hoc }}</option>
                        @php
                            $subjects[] = $exam->monHoc->ma_mon_hoc;
                        @endphp
                    @endif
                @endforeach
            </select>
        </div>

        <ul class="list-exams">
            @foreach($exams as $exam)
                <li class="exam-item" data-subject="{{ $exam->monHoc->ma_mon_hoc }}">
                    <div class="exam-content">
                        <h2>{{ $exam->ten_bai_thi }}</h2>
                        <div class="exam-details">
                            <p><strong>Môn học:</strong> {{ $exam->monHoc->ten_mon_hoc }}</p>
                            <p><strong>Số câu hỏi:</strong> {{ $exam->tong_so_cau }}</p>
                            <p><strong>Thời gian:</strong> {{ $exam->thoi_gian }} phút</p>
                        </div>
                    </div>
                    <div class="exam-actions">
                        <a href="{{ route('exams.show', $exam->slug) }}" class="btn-primary">Chi tiết</a>
                        
                        @if(Auth::user()->vai_tro == 'hoc_sinh')
                            <a href="{{ route('exams.take', $exam->slug) }}" class="btn-primary">Làm bài</a>
                        @endif
                        
                        @if(Auth::user()->vai_tro != 'hoc_sinh' && 
                            (Auth::user()->vai_tro == 'quan_tri' || Auth::user()->ma_nguoi_dung == $exam->nguoi_tao))
                            <a href="{{ route('exams.edit', $exam->slug) }}" class="btn-primary">Sửa</a>
                            
                            <form action="{{ route('exams.destroy', $exam->slug) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-primary" onclick="return confirm('Bạn có chắc chắn muốn xóa bài thi này?')">Xóa</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p>Không có bài thi nào.</p>
    @endif
@endsection

@section('styles')
<style>
    body {
        font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
    }
    .exam-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem 2rem 0 2rem;
    }
    .exam-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #3b3b5c;
        letter-spacing: 1px;
    }
    .btn-primary {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        transition: background 0.2s, box-shadow 0.2s, transform 0.1s;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #60a5fa 0%, #6366f1 100%);
        box-shadow: 0 4px 16px rgba(99,102,241,0.15);
        transform: translateY(-2px) scale(1.03);
    }
    .exam-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 0 2rem;
    }
    .search-input, .filter-select {
        padding: 0.7rem 1rem;
        border: 1px solid #c7d2fe;
        border-radius: 8px;
        font-size: 1rem;
        background: #fff;
        transition: border 0.2s;
        box-shadow: 0 1px 4px rgba(99,102,241,0.04);
    }
    .search-input:focus, .filter-select:focus {
        border: 1.5px solid #6366f1;
        outline: none;
    }
    .list-exams {
        list-style-type: none;
        padding: 0 2rem 2rem 2rem;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 2rem;
    }
    .exam-item {
        background: linear-gradient(120deg, #fff 60%, #e0e7ff 100%);
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(99,102,241,0.10);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 220px;
        transition: box-shadow 0.2s, transform 0.15s;
        position: relative;
        overflow: hidden;
    }
    .exam-item:hover {
        box-shadow: 0 8px 32px rgba(99,102,241,0.18);
        transform: translateY(-4px) scale(1.02);
    }
    .exam-content h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #3730a3;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }
    .exam-details {
        margin-top: 0.5rem;
        color: #6366f1;
        font-size: 1rem;
    }
    .exam-details p {
        margin: 0.2rem 0;
    }
    .exam-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1.2rem;
    }
    .exam-actions .btn-primary {
        font-size: 0.98rem;
        padding: 0.5rem 1.1rem;
    }
    @media (max-width: 700px) {
        .exam-header, .exam-filters, .list-exams {
            padding: 0 0.5rem;
        }
        .list-exams {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchExam');
        const subjectFilter = document.getElementById('subjectFilter');
        const examItems = document.querySelectorAll('.exam-item');
        
        function filterExams() {
            const searchTerm = searchInput.value.toLowerCase();
            const subjectId = subjectFilter.value;
            
            examItems.forEach(item => {
                const title = item.querySelector('h2').textContent.toLowerCase();
                const itemSubjectId = item.dataset.subject;
                
                const matchesSearch = title.includes(searchTerm);
                const matchesSubject = subjectId === '' || itemSubjectId === subjectId;
                
                if (matchesSearch && matchesSubject) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterExams);
        subjectFilter.addEventListener('change', filterExams);
    });
</script>
@endsection