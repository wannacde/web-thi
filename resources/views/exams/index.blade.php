@extends('layout.main')

@section('title', 'Danh sách bài thi')

@section('content')
    <div class="exam-header">
        <h1>Danh sách bài thi</h1>
        
        @if(Auth::user() && Auth::user()->vai_tro != 'hoc_sinh')
            <a href="{{ route('exam.create') }}" class="btn-primary">Tạo bài thi mới</a>
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
                        <a href="{{ route('exam.detail', $exam->ma_bai_thi) }}" class="btn-primary">Chi tiết</a>
                        
                        @if(Auth::user()->vai_tro == 'hoc_sinh')
                            <a href="{{ route('exam.take', $exam->ma_bai_thi) }}" class="btn-primary">Làm bài</a>
                        @endif
                        
                        @if(Auth::user()->vai_tro != 'hoc_sinh' && 
                            (Auth::user()->vai_tro == 'quan_tri' || Auth::user()->ma_nguoi_dung == $exam->nguoi_tao))
                            <a href="{{ route('exam.edit', $exam->ma_bai_thi) }}" class="btn-primary">Sửa</a>
                            
                            <form action="{{ route('exam.destroy', $exam->ma_bai_thi) }}" method="POST" style="display: inline;">
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
    .exam-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .exam-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .search-input, .filter-select {
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .search-input {
        flex-grow: 1;
    }
    .exam-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
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