@extends('layout.main')

@section('title', 'Danh sách câu hỏi')

@section('content')
    <div class="questions-header">
        <h1>Danh sách câu hỏi</h1>
        <a href="{{ route('questions.create') }}" class="btn-primary">Thêm câu hỏi mới</a>
    </div>

    <div class="questions-filters">
        <select id="subjectFilter" class="filter-select">
            <option value="">Tất cả môn học</option>
            @foreach($subjects as $subject)
                <option value="{{ $subject->ma_mon_hoc }}">
                    {{ $subject->ten_mon_hoc }}
                </option>
            @endforeach
        </select>
        
        <select id="chapterFilter" class="filter-select" disabled>
            <option value="">Tất cả chương</option>
        </select>
        
        <select id="typeFilter" class="filter-select">
            <option value="">Tất cả loại</option>
            <option value="trac_nghiem">Trắc nghiệm</option>
            <option value="dien_khuyet">Điền khuyết</option>
        </select>
        
        <input type="text" id="searchQuestion" placeholder="Tìm kiếm câu hỏi..." class="search-input">
        
        <button id="searchButton" class="btn-primary">Tìm kiếm</button>
    </div>

    <div id="searchResults">
        @include('questions.partials.question-list')
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
    .questions-header h1:before {
        content: '\f02d';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .questions-list li {
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
        align-items: center;
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
        gap: 5px;
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
        gap: 5px;
    }
    .pagination li {
        list-style: none !important;
    }
    .pagination li::marker {
        display: none !important;
        content: none !important;
    }
    .pagination .page-link {
        padding: 5px 10px;
        color: #333;
        text-decoration: none;
        background: none;
        border-radius: 4px;
        display: inline-block;
        min-width: 35px;
        text-align: center;
    }
    .pagination .page-item.active .page-link {
        font-weight: bold;
        color: #007bff !important;
    }
    .pagination .page-item.disabled .page-link {
        color: #ccc;
        pointer-events: none;
    }
    #searchResults {
        min-height: 200px;
    }
    .no-results {
        text-align: center;
        padding: 20px;
        font-weight: bold;
        color: #6c757d;
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
            chapterFilter.innerHTML = '<option value="">Tất cả chương</option>';
            return;
        }
        
        fetch(`/chuong/${selectedSubject}`)
            .then(response => response.json())
            .then(chuongs => {
                let options = '<option value="">Tất cả chương</option>';
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
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        throw new Error('Phiên đăng nhập đã hết hạn hoặc có lỗi máy chủ. Vui lòng tải lại trang.');
                    }
                })
                .then(data => {
                    if (data.success) {
                        searchResults.innerHTML = data.html;
                    } else {
                        throw new Error(data.message || 'Đã xảy ra lỗi');
                    }
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    if (error.message.includes('Phiên đăng nhập đã hết hạn') || error.message.includes('máy chủ')) {
                        searchResults.innerHTML = `<div class=\"text-center\"><p>Phiên đăng nhập đã hết hạn hoặc có lỗi máy chủ.<br><button id='reloadPageBtn' style='margin-top:8px;padding:6px 16px;background:#3490dc;color:#fff;border:none;border-radius:4px;cursor:pointer;'>Tải lại trang</button></p></div>`;
                        setTimeout(function() {
                            const reloadBtn = document.getElementById('reloadPageBtn');
                            if (reloadBtn) reloadBtn.onclick = function() { location.reload(); };
                        }, 100);
                    } else {
                        searchResults.innerHTML = `<div class=\"text-center\"><p>Đã xảy ra lỗi: ${error.message}</p></div>`;
                    }
                });
            }
        }
    });
});
</script>
@endsection