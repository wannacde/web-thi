@extends('layout.main')

@section('title', 'Chỉnh sửa bài thi')

@section('content')
    <h1>Chỉnh sửa bài thi</h1>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('exams.update', $exam->slug) }}" id="editExamForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="ten_bai_thi">Tên bài thi</label>
            <input type="text" id="ten_bai_thi" name="ten_bai_thi" value="{{ old('ten_bai_thi', $exam->ten_bai_thi) }}" required>
        </div>

        <div class="form-group">
            <label for="ma_mon_hoc">Môn học</label>
            <select id="ma_mon_hoc" name="ma_mon_hoc" required>
                <option value="">-- Chọn môn học --</option>
                @foreach($subjects as $monHoc)
                    <option value="{{ $monHoc->ma_mon_hoc }}" {{ old('ma_mon_hoc', $exam->ma_mon_hoc) == $monHoc->ma_mon_hoc ? 'selected' : '' }}>
                        {{ $monHoc->ten_mon_hoc }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tong_so_cau">Tổng số câu hỏi</label>
            <input type="number" id="tong_so_cau" name="tong_so_cau" value="{{ old('tong_so_cau', $exam->tong_so_cau) }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="thoi_gian">Thời gian làm bài (phút)</label>
            <input type="number" id="thoi_gian" name="thoi_gian" value="{{ old('thoi_gian', $exam->thoi_gian) }}" min="1" required>
        </div>

        <div class="form-group">
            <label>Chọn câu hỏi</label>
            <div id="questionSelectionContainer">
                <div class="loading-indicator">Đang tải câu hỏi...</div>
                <div id="questionList" style="display: none;">
                    <!-- Danh sách câu hỏi sẽ được tải bằng JavaScript -->
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Cập nhật bài thi</button>
            <a href="{{ route('exams.show', $exam->slug) }}" class="btn-secondary">Hủy</a>
        </div>
    </form>
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
    .form-actions .btn-primary i {
        margin-right: 0.5rem;
    }
    .form-group label:before {
        content: '\f044';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .question-item {
        background: #fff;
        border-left: 4px solid #3490dc;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(52,144,220,0.08);
        padding: 1rem 1.5rem;
        border-radius: 8px;
    }
    .form-actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
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
    .loading-indicator {
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 4px;
        text-align: center;
    }
    #questionList {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 1rem;
        margin-top: 1rem;
    }
    .question-item {
        padding: 0.75rem;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: flex-start;
    }
    .question-item:last-child {
        border-bottom: none;
    }
    .question-checkbox {
        margin-right: 0.75rem;
        margin-top: 0.25rem;
    }
    .question-content {
        flex-grow: 1;
    }
    .question-content h4 {
        margin: 0 0 0.5rem 0;
    }
    .question-meta {
        font-size: 0.875rem;
        color: #6c757d;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monHocSelect = document.getElementById('ma_mon_hoc');
        const questionList = document.getElementById('questionList');
        const loadingIndicator = document.querySelector('.loading-indicator');
        
        // Mảng chứa ID các câu hỏi đã được chọn
        const selectedQuestions = @json($selectedQuestions);
        
        // Tải câu hỏi khi trang được tải
        loadQuestions(monHocSelect.value);
        
        monHocSelect.addEventListener('change', function() {
            loadQuestions(this.value);
        });
        
        function loadQuestions(monHocId) {
            if (!monHocId) {
                loadingIndicator.textContent = 'Vui lòng chọn môn học để hiển thị câu hỏi...';
                loadingIndicator.style.display = 'block';
                questionList.style.display = 'none';
                return;
            }
            
            loadingIndicator.textContent = 'Đang tải câu hỏi...';
            loadingIndicator.style.display = 'block';
            questionList.style.display = 'none';
            
            // Lấy danh sách chương theo môn học
            fetch(`/chuong/${monHocId}`)
                .then(response => response.json())
                .then(chuongs => {
                    if (chuongs.length === 0) {
                        loadingIndicator.textContent = 'Không có chương nào cho môn học này.';
                        return;
                    }
                    
                    // Lấy tất cả câu hỏi từ các chương
                    const promises = chuongs.map(chuong => 
                        fetch(`/questions/by-chuong/${chuong.ma_chuong}`)
                            .then(response => response.json())
                    );
                    
                    Promise.all(promises)
                        .then(results => {
                            // Gộp tất cả câu hỏi
                            let allQuestions = [];
                            results.forEach(questions => {
                                allQuestions = allQuestions.concat(questions);
                            });
                            
                            if (allQuestions.length === 0) {
                                loadingIndicator.textContent = 'Không có câu hỏi nào cho môn học này.';
                                return;
                            }
                            
                            // Hiển thị danh sách câu hỏi
                            renderQuestions(allQuestions, chuongs);
                            loadingIndicator.style.display = 'none';
                            questionList.style.display = 'block';
                        })
                        .catch(error => {
                            console.error('Error fetching questions:', error);
                            loadingIndicator.textContent = 'Đã xảy ra lỗi khi tải câu hỏi.';
                        });
                })
                .catch(error => {
                    console.error('Error fetching chapters:', error);
                    loadingIndicator.textContent = 'Đã xảy ra lỗi khi tải chương.';
                });
        }
        
        function renderQuestions(questions, chuongs) {
            // Tạo map chương để dễ dàng tra cứu
            const chuongMap = {};
            chuongs.forEach(chuong => {
                chuongMap[chuong.ma_chuong] = chuong;
            });
            
            let html = '';
            
            questions.forEach(question => {
                const chuong = chuongMap[question.ma_chuong];
                const isSelected = selectedQuestions.includes(question.ma_cau_hoi);
                
                html += `
                <div class="question-item">
                    <input type="checkbox" name="cau_hoi[]" value="${question.ma_cau_hoi}" class="question-checkbox" ${isSelected ? 'checked' : ''}>
                    <div class="question-content">
                        <h4>${question.noi_dung}</h4>
                        <div class="question-meta">
                            <span>Chương: ${chuong ? chuong.ten_chuong : 'Không xác định'}</span> | 
                            <span>Loại: ${question.loai_cau_hoi === 'trac_nghiem' ? 'Trắc nghiệm' : 'Điền khuyết'}</span>
                        </div>
                    </div>
                </div>
                `;
            });
            
            questionList.innerHTML = html;
        }
        
        // Kiểm tra số lượng câu hỏi được chọn khi submit form
        document.getElementById('editExamForm').addEventListener('submit', function(e) {
            const tongSoCau = parseInt(document.getElementById('tong_so_cau').value);
            const selectedQuestions = document.querySelectorAll('input[name="cau_hoi[]"]:checked');
            
            if (selectedQuestions.length < tongSoCau) {
                e.preventDefault();
                alert(`Bạn cần chọn ít nhất ${tongSoCau} câu hỏi cho bài thi này.`);
            } else if (selectedQuestions.length > tongSoCau) {
                e.preventDefault();
                alert(`Bạn chỉ được chọn tối đa ${tongSoCau} câu hỏi cho bài thi này.`);
            }
        });
    });
</script>
@endsection