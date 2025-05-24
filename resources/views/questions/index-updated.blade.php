@extends('layout.main')

@section('title', 'Danh sách câu hỏi')

@section('content')
    <div class="questions-header">
        <h1><i class="fas fa-question-circle"></i> Danh sách câu hỏi</h1>
        <a href="{{ route('questions.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm câu hỏi mới</a>
    </div>

    <div class="questions-filters">
        <select id="subjectFilter" class="filter-select">
            <option value=""><i class="fas fa-book"></i> Tất cả môn học</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->ma_mon_hoc }}">
                    {{ $subject->ten_mon_hoc }}
                </option>
            @endforeach
        </select>
        
        <select id="chapterFilter" class="filter-select" disabled>
            <option value=""><i class="fas fa-layer-group"></i> Tất cả chương</option>
        </select>
        
        <select id="typeFilter" class="filter-select">
            <option value=""><i class="fas fa-filter"></i> Tất cả loại</option>
            <option value="trac_nghiem">Trắc nghiệm</option>
            <option value="dien_khuyet">Điền khuyết</option>
        </select>
        
        <div class="search-container">
            <input type="text" id="searchQuestion" placeholder="Tìm kiếm câu hỏi..." class="search-input">
            <button id="searchButton" class="search-btn"><i class="fas fa-search"></i></button>
        </div>
    </div>

    <div id="searchResults">
        @include('questions.partials.question-list-updated')
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
    .questions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .questions-header h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .questions-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        align-items: center;
        flex-wrap: wrap;
    }
    .search-input, .filter-select {
        padding: 0.7rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        font-family: 'Montserrat', sans-serif;
        transition: all 0.2s;
    }
    .filter-select {
        min-width: 150px;
        cursor: pointer;
    }
    .search-container {
        display: flex;
        flex-grow: 1;
        position: relative;
    }
    .search-input {
        flex-grow: 1;
        padding-right: 40px;
    }
    .search-btn {
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 40px;
        background: none;
        border: none;
        color: #3490dc;
        cursor: pointer;
        font-size: 1rem;
    }
    .search-input:focus, .filter-select:focus {
        outline: none;
        border-color: #3490dc;
        box-shadow: 0 0 0 3px rgba(52,144,220,0.2);
    }
    .btn-primary {
        background-color: #3490dc;
        color: white;
        border: none;
        padding: 0.7rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s;
        box-shadow: 0 4px 6px rgba(52,144,220,0.15);
    }
    .btn-primary:hover {
        background-color: #2779bd;
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(52,144,220,0.2);
    }
    .list-questions {
        list-style-type: none;
        padding: 0;
    }
    .question-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1.5rem;
        background-color: #fff;
        border-radius: 12px;
        margin-bottom: 1.2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: all 0.2s;
        border-left: 4px solid #3490dc;
    }
    .question-item:hover {
        box-shadow: 0 8px 15px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .question-content {
        flex-grow: 1;
    }
    .question-content h3 {
        margin-top: 0;
        margin-bottom: 0.8rem;
        color: #2d3748;
        font-size: 1.2rem;
    }
    .question-meta {
        margin: 0.5rem 0;
        font-size: 0.875rem;
        color: #6c757d;
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }
    .question-meta span {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .question-actions {
        display: flex;
        gap: 0.8rem;
        margin-left: 1.5rem;
    }
    .icon-btn {
        background: #f8fafc;
        border: none;
        color: #3490dc;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        cursor: pointer;
        text-decoration: none;
    }
    .icon-btn:hover {
        background: #3490dc;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(52,144,220,0.2);
    }
    .answers {
        margin-top: 1rem;
        background-color: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
    }
    .answers strong {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #2d3748;
    }
    .answers ul {
        list-style-type: none;
        padding-left: 1.5rem;
        margin-top: 0.8rem;
    }
    .answers li {
        margin-bottom: 0.5rem;
        padding: 0.5rem 0.8rem;
        background-color: #fff;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .correct-answer {
        background-color: #d4edda !important;
        border-left: 3px solid #28a745;
    }
    .correct-badge {
        background-color: #28a745;
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        margin-left: 0.5rem;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    .pagination nav {
        display: flex;
        justify-content: center;
        border: none !important;
        background: none !important;
    }
    .pagination ul {
        display: flex;
        list-style: none !important;
        padding: 0;
        margin: 0;
        gap: 0.5rem;
    }
    .pagination li {
        list-style: none !important;
    }
    .pagination li::marker {
        display: none !important;
        content: none !important;
    }
    .pagination .page-link {
        padding: 0.5rem 1rem;
        color: #3490dc;
        text-decoration: none;
        background: #fff;
        border-radius: 8px;
        display: inline-block;
        min-width: 40px;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    .pagination .page-link:hover {
        background: #e6f0ff;
    }
    .pagination .page-item.active .page-link {
        background: #3490dc;
        color: #fff !important;
    }
    .pagination .page-item.disabled .page-link {
        color: #ccc;
        pointer-events: none;
        background: #f8fafc;
    }
    #searchResults {
        min-height: 200px;
    }
    .no-results {
        text-align: center;
        padding: 3rem 0;
        color: #6c757d;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .no-results p {
        font-size: 1.2rem;
        font-weight: 600;
    }
    .inline-form {
        display: inline;
    }
    @media (max-width: 768px) {
        .questions-filters {
            flex-direction: column;
            align-items: stretch;
        }
        .search-container {
            width: 100%;
        }
        .question-item {
            flex-direction: column;
        }
        .question-actions {
            margin-left: 0;
            margin-top: 1rem;
            justify-content: flex-end;
            width: 100%;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchQuestion');
    const subjectFilter = document.getElementById('subjectFilter');
    const chapterFilter = document.getElementById('chapterFilter');
    const typeFilter = document.getElementById('typeFilter');
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle subject change
    subjectFilter.addEventListener('change', function() {
        const selectedSubject = this.value;
        
        if (!selectedSubject) {
            chapterFilter.disabled = true;
            chapterFilter.innerHTML = '<option value=""><i class="fas fa-layer-group"></i> Tất cả chương</option>';
            return;
        }
        
        fetch(`/chuong/${selectedSubject}`)
            .then(response => response.json())
            .then(chuongs => {
                let options = '<option value=""><i class="fas fa-layer-group"></i> Tất cả chương</option>';
                chuongs.forEach(chuong => {
                    options += `<option value="${chuong.ma_chuong}">${chuong.ten_chuong}</option>`;
                });
                chapterFilter.innerHTML = options;
                chapterFilter.disabled = false;
            })
            .catch(error => {
                console.error('Lỗi khi lấy danh sách chương:', error);
                chapterFilter.disabled = true;
            });
    });
    
    // Handle search button click
    searchButton.addEventListener('click', performSearch);
    
    // Handle enter key in search input
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });

    function performSearch() {
        const searchTerm = searchInput.value;
        const subjectId = subjectFilter.value;
        const chapterId = chapterFilter.value;
        const questionType = typeFilter.value;

        // Build query string
        const params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (subjectId) params.append('subject', subjectId);
        if (chapterId) params.append('chapter', chapterId);
        if (questionType) params.append('type', questionType);

        // Show loading
        searchResults.innerHTML = '<div class="text-center"><p>Đang tìm kiếm...</p></div>';

        // Send AJAX request
        fetch(`/questions/search?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                if (data.success) {
                    searchResults.innerHTML = data.html;
                } else {
                    throw new Error(data.message || 'Không tìm thấy kết quả');
                }
            } else {
                const html = await response.text();
                searchResults.innerHTML = html;
            }
        })
        .catch(error => {
            console.error('Lỗi:', error);
            searchResults.innerHTML = `<div class="text-center"><p>Đã xảy ra lỗi: ${error.message}</p></div>`;
        });
    }

    // Handle pagination
    document.body.addEventListener('click', function(e) {
        const target = e.target;
        
        if (target.tagName === 'A' && target.closest('.pagination')) {
            e.preventDefault();
            const url = target.getAttribute('href');
            
            if (url) {
                searchResults.innerHTML = '<div class="text-center"><p>Đang tải...</p></div>';
                
                fetch(url, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        const data = await response.json();
                        if (data.success) {
                            searchResults.innerHTML = data.html;
                        } else {
                            throw new Error(data.message || 'Đã xảy ra lỗi');
                        }
                    } else {
                        const html = await response.text();
                        searchResults.innerHTML = html;
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    searchResults.innerHTML = `<div class="text-center"><p>Đã xảy ra lỗi: ${error.message}</p></div>`;
                });
            }
        }
    });
});
</script>
@endsection