@extends('layout.main')

@section('title', 'Danh sách câu hỏi')

@section('content')
    <div class="questions-header">
        <h1>Danh sách câu hỏi</h1>
        <a href="{{ route('questions.create') }}" class="btn-primary">Thêm câu hỏi mới</a>
    </div>

    <div class="questions-filters">
        <input type="text" id="searchQuestion" placeholder="Tìm kiếm câu hỏi..." class="search-input">
        <select id="chapterFilter" class="filter-select">
            <option value="">Tất cả chương</option>
            @php
                $chapters = [];
            @endphp
            @foreach($questions as $question)
                @if(!in_array($question->chuong->ma_chuong, $chapters))
                    <option value="{{ $question->chuong->ma_chuong }}">
                        {{ $question->chuong->monHoc->ten_mon_hoc }} - {{ $question->chuong->ten_chuong }}
                    </option>
                    @php
                        $chapters[] = $question->chuong->ma_chuong;
                    @endphp
                @endif
            @endforeach
        </select>
        <select id="typeFilter" class="filter-select">
            <option value="">Tất cả loại</option>
            <option value="trac_nghiem">Trắc nghiệm</option>
            <option value="dien_khuyet">Điền khuyết</option>
        </select>
    </div>

    @if(count($questions) > 0)
        <ul class="list-questions">
            @foreach($questions as $question)
                <li class="question-item" data-chapter="{{ $question->chuong->ma_chuong }}" data-type="{{ $question->loai_cau_hoi }}">
                    <div class="question-content">
                        <h3>{{ $question->noi_dung }}</h3>
                        <div class="question-meta">
                            <span>Môn học: {{ $question->chuong->monHoc->ten_mon_hoc }}</span> |
                            <span>Chương: {{ $question->chuong->ten_chuong }}</span> |
                            <span>Loại: {{ $question->loai_cau_hoi == 'trac_nghiem' ? 'Trắc nghiệm' : 'Điền khuyết' }}</span>
                        </div>
                        
                        @if(count($question->dapAn) > 0)
                            <div class="answers">
                                <strong>Đáp án:</strong>
                                <ul>
                                    @foreach($question->dapAn as $dapAn)
                                        <li class="{{ $dapAn->dung_sai ? 'correct-answer' : '' }}">
                                            {{ $dapAn->noi_dung }}
                                            @if($dapAn->dung_sai)
                                                <span class="correct-badge">Đúng</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    
                    <div class="question-actions">
                        <a href="{{ route('questions.edit', $question->ma_cau_hoi) }}" class="btn-primary">Sửa</a>
                        <form action="{{ route('questions.destroy', $question->ma_cau_hoi) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-primary" onclick="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?')">Xóa</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
        
        @if(method_exists($questions, 'links'))
            <div class="pagination">
                {{ $questions->links() }}
            </div>
        @endif
    @else
        <p>Không có câu hỏi nào.</p>
    @endif
@endsection

@section('styles')
<style>
    .questions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .questions-filters {
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
    .list-questions {
        list-style-type: none;
        padding: 0;
    }
    .question-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    .question-content {
        flex-grow: 1;
    }
    .question-meta {
        margin: 0.5rem 0;
        font-size: 0.875rem;
        color: #6c757d;
    }
    .question-actions {
        display: flex;
        gap: 0.5rem;
        margin-left: 1rem;
    }
    .answers {
        margin-top: 0.75rem;
    }
    .answers ul {
        list-style-type: none;
        padding-left: 1rem;
        margin-top: 0.5rem;
    }
    .answers li {
        margin-bottom: 0.25rem;
        padding: 0.25rem 0.5rem;
        background-color: #fff;
        border-radius: 4px;
    }
    .correct-answer {
        background-color: #d4edda;
    }
    .correct-badge {
        background-color: #28a745;
        color: white;
        padding: 0.1rem 0.25rem;
        border-radius: 4px;
        margin-left: 0.5rem;
        font-size: 0.75rem;
    }
    .pagination {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchQuestion');
        const chapterFilter = document.getElementById('chapterFilter');
        const typeFilter = document.getElementById('typeFilter');
        const questionItems = document.querySelectorAll('.question-item');
        
        function filterQuestions() {
            const searchTerm = searchInput.value.toLowerCase();
            const chapterId = chapterFilter.value;
            const questionType = typeFilter.value;
            
            questionItems.forEach(item => {
                const content = item.querySelector('.question-content').textContent.toLowerCase();
                const itemChapterId = item.dataset.chapter;
                const itemType = item.dataset.type;
                
                const matchesSearch = content.includes(searchTerm);
                const matchesChapter = chapterId === '' || itemChapterId === chapterId;
                const matchesType = questionType === '' || itemType === questionType;
                
                if (matchesSearch && matchesChapter && matchesType) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterQuestions);
        chapterFilter.addEventListener('change', filterQuestions);
        typeFilter.addEventListener('change', filterQuestions);
    });
</script>
@endsection