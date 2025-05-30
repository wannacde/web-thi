@extends('layout.main')

@section('title', 'Tạo bài thi ngẫu nhiên')

@section('content')
    <h1>Tạo bài thi ngẫu nhiên</h1>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="loading-indicator" style="display: none;">
        <div class="loading-spinner"></div>
        <p>Đang tạo câu hỏi ngẫu nhiên, vui lòng đợi...</p>
    </div>

    <div id="step1-container">
        <form id="generateQuestionsForm">
            @csrf
            <div class="form-group">
                <label for="ten_bai_thi">Tên bài thi</label>
                <input type="text" id="ten_bai_thi" name="ten_bai_thi" value="{{ old('ten_bai_thi') }}" required>
            </div>

            <div class="form-group">
                <label for="ma_mon_hoc">Môn học</label>
                <select id="ma_mon_hoc" name="ma_mon_hoc" class="form-control" required>
                    <option value="">-- Chọn môn học --</option>
                    @foreach($subjects as $monHoc)
                        <option value="{{ $monHoc->ma_mon_hoc }}">{{ $monHoc->ten_mon_hoc }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tong_so_cau">Tổng số câu hỏi</label>
                <input type="number" id="tong_so_cau" name="tong_so_cau" class="form-control" min="1" value="{{ old('tong_so_cau', 10) }}" required>
            </div>

            <div class="form-group">
                <label for="thoi_gian">Thời gian làm bài (phút)</label>
                <input type="number" id="thoi_gian" name="thoi_gian" class="form-control" min="1" value="{{ old('thoi_gian', 30) }}" required>
            </div>

            <div id="chapters-container" class="mt-4" style="display: none;">
                <h3>Phân bổ câu hỏi theo chương</h3>
                <p class="text-muted">Tổng số câu hỏi đã phân bổ: <span id="total-allocated">0</span>/<span id="total-questions">0</span></p>
                
                <div id="chapters-list" class="chapters-list">
                    <!-- Danh sách chương sẽ được thêm vào đây bằng JavaScript -->
                </div>
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn-primary" id="generate-btn" disabled>Tạo câu hỏi ngẫu nhiên</button>
                <a href="{{ route('exams.index') }}" class="btn-secondary">Hủy</a>
            </div>
        </form>
    </div>

    <div id="step2-container" style="display: none;">
        <h2>Xác nhận câu hỏi đã chọn</h2>
        
        <div class="question-actions">
            <button type="button" class="btn-secondary" id="add-question-btn">
                <i class="fas fa-plus"></i> Thêm câu hỏi
            </button>
            <span class="question-count-info">Đã chọn: <span id="selected-count">0</span> câu hỏi</span>
        </div>
        
        <div id="selected-questions-container">
            <!-- Danh sách câu hỏi đã chọn sẽ được thêm vào đây bằng JavaScript -->
        </div>

        <form id="createExamForm" action="{{ route('exams.store') }}" method="POST">
            @csrf
            <input type="hidden" id="confirm_ten_bai_thi" name="ten_bai_thi">
            <input type="hidden" id="confirm_ma_mon_hoc" name="ma_mon_hoc">
            <input type="hidden" id="confirm_tong_so_cau" name="tong_so_cau">
            <input type="hidden" id="confirm_thoi_gian" name="thoi_gian">
            <div id="selected-questions-inputs">
                <!-- Input hidden cho các câu hỏi đã chọn sẽ được thêm vào đây bằng JavaScript -->
            </div>

            <div class="form-actions mt-4">
                <button type="submit" class="btn-primary">Xác nhận và tạo bài thi</button>
                <button type="button" class="btn-secondary" id="back-btn">Quay lại</button>
                <a href="{{ route('exams.index') }}" class="btn-secondary">Hủy</a>
            </div>
        </form>
    </div>
    
    <!-- Modal thêm câu hỏi -->
    <div id="addQuestionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Thêm câu hỏi</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="chapter-filter">Chọn chương</label>
                    <select id="chapter-filter" class="form-control">
                        <option value="">Tất cả chương</option>
                        <!-- Các chương sẽ được thêm vào đây bằng JavaScript -->
                    </select>
                </div>
                
                <div id="available-questions-container">
                    <div class="loading">Đang tải câu hỏi...</div>
                    <!-- Danh sách câu hỏi có sẵn sẽ được thêm vào đây bằng JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-primary" id="add-selected-questions">Thêm câu hỏi đã chọn</button>
                <button type="button" class="btn-secondary close-modal">Hủy</button>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
        min-height: 100vh;
    }
    h1, h2, h3, h4 {
        font-weight: 600;
        color: #2d3a4a;
        letter-spacing: 0.5px;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: block;
        color: #34495e;
    }
    input[type="text"], input[type="number"], select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        font-size: 1rem;
        transition: box-shadow 0.2s;
        box-shadow: 0 1px 2px rgba(44,62,80,0.04);
    }
    input:focus, select:focus {
        outline: none;
        box-shadow: 0 0 0 2px #a5d8ff;
        border-color: #74b9ff;
    }
    .btn-primary {
        background: linear-gradient(90deg, #74b9ff 0%, #6c63ff 100%);
        color: #fff;
        border: none;
        padding: 0.7rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(76,110,245,0.08);
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #6c63ff 0%, #74b9ff 100%);
        transform: translateY(-2px) scale(1.03);
    }
    .btn-secondary {
        background: #fff;
        color: #6c63ff;
        border: 1px solid #6c63ff;
        padding: 0.7rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, border 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-secondary:hover {
        background: #6c63ff;
        color: #fff;
        border: 1px solid #6c63ff;
    }
    .form-actions {
        margin-top: 2rem;
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .error {
        background: #ffe0e0;
        color: #c0392b;
        border-radius: 6px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(192,57,43,0.06);
    }
    #step1-container, #step2-container {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(44,62,80,0.10);
        padding: 2.5rem 2rem 2rem 2rem;
        max-width: 600px;
        margin: 2rem auto 2.5rem auto;
        position: relative;
        animation: fadeInUp 0.7s cubic-bezier(.39,.575,.56,1) both;
    }
    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(40px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    #loading-indicator {
        text-align: center;
        padding: 2rem;
        background-color: rgba(255, 255, 255, 0.9);
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-size: 1.2rem;
    }
    .loading-spinner {
        border: 5px solid #f3f3f3;
        border-top: 5px solid #6c63ff;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        animation: spin 1.2s linear infinite;
        margin-bottom: 1rem;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .chapters-list {
        margin-top: 1rem;
    }
    .chapter-item {
        background: linear-gradient(90deg, #e0eafc 0%, #cfdef3 100%);
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 8px rgba(44,62,80,0.06);
    }
    .chapter-info h4 {
        margin: 0 0 0.2rem 0;
        font-size: 1.1rem;
        color: #2d3a4a;
    }
    .chapter-difficulty {
        margin-left: 0.5rem;
        padding: 0.2rem 0.7rem;
        border-radius: 4px;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .difficulty-de {
        background: #d1e7dd;
        color: #0f5132;
    }
    .difficulty-trung_binh {
        background: #fff3cd;
        color: #664d03;
    }
    .difficulty-kho {
        background: #f8d7da;
        color: #842029;
    }
    .chapter-input label {
        font-size: 0.95rem;
        color: #34495e;
        margin-right: 0.5rem;
    }
    .chapter-input input {
        width: 70px;
        padding: 0.4rem 0.7rem;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        font-size: 1rem;
        margin-left: 0.3rem;
    }
    #selected-questions-container {
        margin-top: 1rem;
        margin-bottom: 2rem;
    }
    .question-item {
        background: linear-gradient(90deg, #e0eafc 0%, #cfdef3 100%);
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 0.7rem;
        box-shadow: 0 2px 8px rgba(44,62,80,0.06);
        position: relative;
    }
    .question-content {
        margin-bottom: 0.5rem;
        font-size: 1.05rem;
        padding-right: 30px;
    }
    .question-meta {
        font-size: 0.95rem;
        color: #6c757d;
    }
    .answers-list {
        margin-top: 0.5rem;
        padding-left: 1.5rem;
    }
    .answer-item {
        margin-bottom: 0.25rem;
    }
    .answer-item.correct {
        color: #0f5132;
        font-weight: bold;
    }
    .remove-question {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.7);
        border: none;
        color: #dc3545;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .remove-question:hover {
        background: #dc3545;
        color: white;
    }
    .question-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .question-count-info {
        font-size: 1rem;
        color: #6c757d;
        font-weight: 500;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 0;
        border-radius: 10px;
        width: 80%;
        max-width: 800px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        animation: modalFadeIn 0.3s;
    }
    @keyframes modalFadeIn {
        from {opacity: 0; transform: translateY(-30px);}
        to {opacity: 1; transform: translateY(0);}
    }
    .modal-header {
        padding: 1rem 1.5rem;
        background: linear-gradient(90deg, #6c63ff 0%, #74b9ff 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 {
        margin: 0;
        font-weight: 600;
    }
    .modal-body {
        padding: 1.5rem;
        max-height: 60vh;
        overflow-y: auto;
    }
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    .close {
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover {
        color: #f8f9fa;
    }
    .available-question {
        background: #f8f9fa;
        border-left: 4px solid #6c63ff;
        padding: 1rem;
        margin-bottom: 0.5rem;
        border-radius: 4px;
        display: flex;
        align-items: flex-start;
    }
    .available-question input[type="checkbox"] {
        margin-right: 1rem;
        margin-top: 0.25rem;
    }
    #available-questions-container {
        margin-top: 1rem;
        max-height: 400px;
        overflow-y: auto;
    }
    .loading {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }
    .mt-4 {
        margin-top: 1.5rem;
    }
    .text-muted {
        color: #6c757d;
    }
    @media (max-width: 600px) {
        #step1-container, #step2-container {
            padding: 1.2rem 0.5rem 1rem 0.5rem;
        }
        .chapter-item, .question-item {
            flex-direction: column;
            align-items: flex-start;
            padding: 1rem 0.7rem;
        }
        .form-actions {
            flex-direction: column;
            gap: 0.7rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monHocSelect = document.getElementById('ma_mon_hoc');
        const tongSoCauInput = document.getElementById('tong_so_cau');
        const chaptersContainer = document.getElementById('chapters-container');
        const chaptersList = document.getElementById('chapters-list');
        const totalAllocated = document.getElementById('total-allocated');
        const totalQuestions = document.getElementById('total-questions');
        const generateBtn = document.getElementById('generate-btn');
        const generateQuestionsForm = document.getElementById('generateQuestionsForm');
        const step1Container = document.getElementById('step1-container');
        const step2Container = document.getElementById('step2-container');
        const backBtn = document.getElementById('back-btn');
        const loadingIndicator = document.getElementById('loading-indicator');
        
        // Khi chọn môn học
        monHocSelect.addEventListener('change', function() {
            const monHocId = this.value;
            if (monHocId) {
                // Lấy danh sách chương của môn học và số lượng câu hỏi
                fetch(`/random-exam/chapter-question-count?mon_hoc_id=${monHocId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Hiển thị container chương
                        chaptersContainer.style.display = 'block';
                        
                        // Xóa danh sách chương cũ
                        chaptersList.innerHTML = '';
                        
                        // Thêm các chương mới
                        data.forEach(chuong => {
                            const chapterItem = document.createElement('div');
                            chapterItem.className = 'chapter-item';
                            
                            // Hiển thị độ khó của chương
                            let difficultyClass = '';
                            let difficultyText = '';
                            
                            switch(chuong.muc_do) {
                                case 'de':
                                    difficultyClass = 'difficulty-de';
                                    difficultyText = 'Dễ';
                                    break;
                                case 'trung_binh':
                                    difficultyClass = 'difficulty-trung_binh';
                                    difficultyText = 'Trung bình';
                                    break;
                                case 'kho':
                                    difficultyClass = 'difficulty-kho';
                                    difficultyText = 'Khó';
                                    break;
                            }
                            
                            chapterItem.innerHTML = `
                                <div class="chapter-info">
                                    <h4>${chuong.ten_chuong}</h4>
                                    <span class="chapter-difficulty ${difficultyClass}">${difficultyText}</span>
                                    <span class="question-count">(Có sẵn: ${chuong.so_cau_hoi} câu hỏi)</span>
                                </div>
                                <div class="chapter-input">
                                    <label>Số câu hỏi:</label>
                                    <input type="number" name="chuong_so_luong[${chuong.ma_chuong}]" 
                                        class="form-control chapter-count" min="0" max="${chuong.so_cau_hoi}" 
                                        value="0" style="width: 80px;" 
                                        data-max="${chuong.so_cau_hoi}">
                                </div>
                            `;
                            
                            chaptersList.appendChild(chapterItem);
                        });
                        
                        // Thêm sự kiện cho các input số lượng câu hỏi
                        const chapterCountInputs = document.querySelectorAll('.chapter-count');
                        chapterCountInputs.forEach(input => {
                            input.addEventListener('input', function() {
                                const max = parseInt(this.dataset.max);
                                const value = parseInt(this.value) || 0;
                                
                                if (value > max) {
                                    alert(`Chỉ có ${max} câu hỏi có sẵn cho chương này. Vui lòng nhập số nhỏ hơn hoặc bằng ${max}.`);
                                    this.value = max;
                                }
                                
                                updateTotalAllocated();
                            });
                        });
                        
                        // Cập nhật tổng số câu hỏi
                        totalQuestions.textContent = tongSoCauInput.value;
                        updateTotalAllocated();
                    });
            } else {
                chaptersContainer.style.display = 'none';
            }
        });
        
        // Khi thay đổi tổng số câu hỏi
        tongSoCauInput.addEventListener('input', function() {
            totalQuestions.textContent = this.value;
            updateTotalAllocated();
            
            // Kiểm tra và điều chỉnh số lượng câu hỏi đã phân bổ nếu cần
            adjustAllocationIfNeeded();
        });
        
        // Cập nhật tổng số câu hỏi đã phân bổ
        function updateTotalAllocated() {
            const chapterCountInputs = document.querySelectorAll('.chapter-count');
            let total = 0;
            
            chapterCountInputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            
            totalAllocated.textContent = total;
            
            // Kiểm tra nếu tổng số câu hỏi đã phân bổ bằng tổng số câu hỏi
            const tongSoCau = parseInt(tongSoCauInput.value) || 0;
            
            if (total === tongSoCau && tongSoCau > 0) {
                generateBtn.disabled = false;
            } else {
                generateBtn.disabled = true;
            }
            
            // Cập nhật số lượng câu hỏi tối thiểu
            document.getElementById('confirm_tong_so_cau').value = tongSoCau;
        }
        
        // Điều chỉnh số lượng câu hỏi đã phân bổ nếu vượt quá tổng số câu hỏi mới
        function adjustAllocationIfNeeded() {
            const chapterCountInputs = document.querySelectorAll('.chapter-count');
            let total = 0;
            
            chapterCountInputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            
            const tongSoCau = parseInt(tongSoCauInput.value) || 0;
            
            if (total > tongSoCau) {
                alert('Tổng số câu hỏi đã giảm. Vui lòng điều chỉnh lại số lượng câu hỏi cho từng chương.');
                
                // Reset tất cả các input về 0
                chapterCountInputs.forEach(input => {
                    input.value = 0;
                });
                
                updateTotalAllocated();
            }
        }
        
        // Xử lý form xác nhận tạo bài thi
        document.getElementById('createExamForm').addEventListener('submit', function(e) {
            const tongSoCau = parseInt(document.getElementById('confirm_tong_so_cau').value) || 0;
            
            if (selectedQuestions.length < tongSoCau) {
                e.preventDefault();
                alert(`Bạn cần chọn ít nhất ${tongSoCau} câu hỏi cho bài thi này.`);
                return;
            }
        });
        
        // Xử lý form tạo câu hỏi ngẫu nhiên
        generateQuestionsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const tongSoCau = parseInt(tongSoCauInput.value) || 0;
            const chapterCountInputs = document.querySelectorAll('.chapter-count');
            let total = 0;
            
            chapterCountInputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            
            if (total !== tongSoCau) {
                alert('Tổng số câu hỏi đã phân bổ phải bằng tổng số câu hỏi đã nhập');
                return;
            }
            
            // Lấy dữ liệu form
            const formData = new FormData(generateQuestionsForm);
            
            // Hiển thị thông báo đang tải
            loadingIndicator.style.display = 'flex';
            
            // Gửi request để lấy câu hỏi ngẫu nhiên
            fetch('/random-exam/generate-questions', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                // Ẩn thông báo đang tải
                loadingIndicator.style.display = 'none';
                
                if (data.success) {
                    // Hiển thị các câu hỏi đã chọn
                    displaySelectedQuestions(data.questions);
                    
                    // Chuyển sang bước 2
                    step1Container.style.display = 'none';
                    step2Container.style.display = 'block';
                    
                    // Cập nhật các trường hidden trong form xác nhận
                    document.getElementById('confirm_ten_bai_thi').value = document.getElementById('ten_bai_thi').value;
                    document.getElementById('confirm_ma_mon_hoc').value = document.getElementById('ma_mon_hoc').value;
                    document.getElementById('confirm_tong_so_cau').value = document.getElementById('tong_so_cau').value;
                    document.getElementById('confirm_thoi_gian').value = document.getElementById('thoi_gian').value;
                } else {
                    alert('Đã xảy ra lỗi: ' + data.message);
                }
            })
            .catch(error => {
                // Ẩn thông báo đang tải
                loadingIndicator.style.display = 'none';
                
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi tạo câu hỏi ngẫu nhiên');
            });
        });
        
        // Biến toàn cục để lưu trữ danh sách câu hỏi đã chọn
        let selectedQuestions = [];
        let allChapters = [];
        let allAvailableQuestions = {};
        
        // Hiển thị các câu hỏi đã chọn
        function displaySelectedQuestions(questions) {
            selectedQuestions = questions;
            updateSelectedQuestions();
        }
        
        // Cập nhật hiển thị câu hỏi đã chọn
        function updateSelectedQuestions() {
            const container = document.getElementById('selected-questions-container');
            container.innerHTML = '';
            
            selectedQuestions.forEach((question, index) => {
                const questionItem = document.createElement('div');
                questionItem.className = 'question-item';
                questionItem.dataset.id = question.ma_cau_hoi;
                
                let answersHtml = '';
                if (question.dap_an && question.dap_an.length > 0) {
                    answersHtml = '<div class="answers-list">';
                    question.dap_an.forEach(answer => {
                        const isCorrect = answer.dung_sai ? ' correct' : '';
                        answersHtml += `<div class="answer-item${isCorrect}">${answer.noi_dung}${answer.dung_sai ? ' (Đáp án đúng)' : ''}</div>`;
                    });
                    answersHtml += '</div>';
                }
                
                questionItem.innerHTML = `
                    <button type="button" class="remove-question" data-id="${question.ma_cau_hoi}">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="question-content">
                        <strong>${index + 1}. ${question.noi_dung}</strong>
                    </div>
                    <div class="question-meta">
                        Chương: ${question.chuong.ten_chuong} | 
                        Loại: ${question.loai_cau_hoi === 'trac_nghiem' ? 'Trắc nghiệm' : 'Điền khuyết'}
                    </div>
                    ${answersHtml}
                `;
                
                container.appendChild(questionItem);
            });
            
            // Cập nhật số lượng câu hỏi đã chọn
            document.getElementById('selected-count').textContent = selectedQuestions.length;
            
            // Cập nhật các input hidden
            updateHiddenInputs();
            
            // Thêm sự kiện cho các nút xóa
            document.querySelectorAll('.remove-question').forEach(button => {
                button.addEventListener('click', function() {
                    const questionId = this.dataset.id;
                    removeQuestion(questionId);
                });
            });
        }
        
        // Cập nhật các input hidden
        function updateHiddenInputs() {
            const selectedQuestionsInputs = document.getElementById('selected-questions-inputs');
            selectedQuestionsInputs.innerHTML = '';
            
            selectedQuestions.forEach((question, index) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `questions[${index}]`;
                input.value = question.ma_cau_hoi;
                selectedQuestionsInputs.appendChild(input);
            });
        }
        
        // Xóa câu hỏi
        function removeQuestion(questionId) {
            selectedQuestions = selectedQuestions.filter(q => q.ma_cau_hoi != questionId);
            updateSelectedQuestions();
        }
        
        // Quay lại bước 1
        backBtn.addEventListener('click', function() {
            step2Container.style.display = 'none';
            step1Container.style.display = 'block';
        });
        
        // Modal thêm câu hỏi
        const addQuestionBtn = document.getElementById('add-question-btn');
        const addQuestionModal = document.getElementById('addQuestionModal');
        const closeModalBtns = document.querySelectorAll('.close, .close-modal');
        const chapterFilter = document.getElementById('chapter-filter');
        const addSelectedQuestionsBtn = document.getElementById('add-selected-questions');
        
        // Mở modal thêm câu hỏi
        addQuestionBtn.addEventListener('click', function() {
            // Hiển thị modal
            addQuestionModal.style.display = 'block';
            
            // Lấy ID môn học từ input hidden, nếu rỗng thì lấy từ select ở bước 1
            let monHocId = document.getElementById('confirm_ma_mon_hoc').value;
            if (!monHocId) {
                // Nếu đang ở bước 1 hoặc hidden input bị rỗng, lấy từ select
                const selectMonHoc = document.getElementById('ma_mon_hoc');
                if (selectMonHoc) {
                    monHocId = selectMonHoc.value;
                }
            }
            console.log("ID môn học:", monHocId); // Thêm log để kiểm tra
            
            // Hiển thị thông báo đang tải
            const container = document.getElementById('available-questions-container');
            container.innerHTML = '<div class="loading">Đang tải câu hỏi...</div>';
            
            // Kiểm tra nếu không có ID môn học
            if (!monHocId) {
                container.innerHTML = '<div class="loading">Không tìm thấy ID môn học. Vui lòng thử lại.</div>';
                return;
            }
            
            // Lấy danh sách chương
            fetch(`/random-exam/chapter-question-count?mon_hoc_id=${monHocId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(chapters => {
                    console.log("Chapters loaded:", chapters.length); // Thêm log để kiểm tra
                    allChapters = chapters;
                    
                    // Xóa các option cũ trong filter chương
                    while (chapterFilter.options.length > 1) {
                        chapterFilter.remove(1);
                    }
                    
                    // Thêm các chương vào filter
                    chapters.forEach(chapter => {
                        const option = document.createElement('option');
                        option.value = chapter.ma_chuong;
                        option.textContent = chapter.ten_chuong;
                        chapterFilter.appendChild(option);
                    });
                    
                    // Lấy tất cả câu hỏi từ các chương
                    const promises = chapters.map(chapter => 
                        fetch(`/questions/by-chuong/${chapter.ma_chuong}`)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`Error fetching questions for chapter ${chapter.ma_chuong}`);
                                }
                                return response.json();
                            })
                            .then(questions => {
                                // Lưu câu hỏi theo chương
                                allAvailableQuestions[chapter.ma_chuong] = questions;
                                return questions;
                            })
                            .catch(error => {
                                console.error(error);
                                return []; // Trả về mảng rỗng nếu có lỗi
                            })
                    );
                    
                    Promise.all(promises)
                        .then(results => {
                            console.log("All questions loaded"); // Thêm log để kiểm tra
                            filterAvailableQuestions();
                        })
                        .catch(error => {
                            console.error('Error fetching questions:', error);
                            container.innerHTML = '<div class="loading">Đã xảy ra lỗi khi tải câu hỏi.</div>';
                        });
                })
                .catch(error => {
                    console.error('Error fetching chapters:', error);
                    container.innerHTML = '<div class="loading">Đã xảy ra lỗi khi tải chương.</div>';
                });
        });
       
        // Đóng modal
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                addQuestionModal.style.display = 'none';
            });
        });
        
        // Đóng modal khi click bên ngoài
        window.addEventListener('click', function(event) {
            if (event.target == addQuestionModal) {
                addQuestionModal.style.display = 'none';
            }
        });
        
        // Lọc câu hỏi theo chương
        chapterFilter.addEventListener('change', function() {
            filterAvailableQuestions();
        });

        // Lọc câu hỏi theo chương
        function filterAvailableQuestions() {
            const chapterId = chapterFilter.value;
            const container = document.getElementById('available-questions-container');
            
            container.innerHTML = '';
            
            let filteredQuestions = [];
            
            // Nếu không chọn chương, hiển thị tất cả câu hỏi
            if (!chapterId) {
                Object.values(allAvailableQuestions).forEach(questions => {
                    filteredQuestions = filteredQuestions.concat(questions);
                });
            } else {
                // Nếu chọn chương, chỉ hiển thị câu hỏi của chương đó
                filteredQuestions = allAvailableQuestions[chapterId] || [];
            }
            
            if (filteredQuestions.length === 0) {
                container.innerHTML = '<div class="loading">Không có câu hỏi nào.</div>';
                return;
            }
            
            // Lọc bỏ các câu hỏi đã được chọn
            const selectedIds = selectedQuestions.map(q => q.ma_cau_hoi);
            filteredQuestions = filteredQuestions.filter(q => !selectedIds.includes(q.ma_cau_hoi));
            
            if (filteredQuestions.length === 0) {
                container.innerHTML = '<div class="loading">Tất cả câu hỏi đã được chọn.</div>';
                return;
            }
            
            // Hiển thị các câu hỏi
            filteredQuestions.forEach(question => {
                const questionItem = document.createElement('div');
                questionItem.className = 'available-question';
                
                questionItem.innerHTML = `
                    <input type="checkbox" class="question-checkbox" value="${question.ma_cau_hoi}">
                    <div>
                        <div class="question-content">
                            <strong>${question.noi_dung}</strong>
                        </div>
                        <div class="question-meta">
                            Chương: ${question.chuong.ten_chuong} | 
                            Loại: ${question.loai_cau_hoi === 'trac_nghiem' ? 'Trắc nghiệm' : 'Điền khuyết'}
                        </div>
                    </div>
                `;
                
                container.appendChild(questionItem);
            });
        }
        
        // Thêm câu hỏi đã chọn
        addSelectedQuestionsBtn.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.question-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Vui lòng chọn ít nhất một câu hỏi.');
                return;
            }
            
            // Lấy ID các câu hỏi đã chọn
            const selectedIds = Array.from(checkboxes).map(cb => cb.value);
            
            // Tìm các câu hỏi tương ứng
            let newQuestions = [];
            Object.values(allAvailableQuestions).forEach(questions => {
                questions.forEach(question => {
                    if (selectedIds.includes(question.ma_cau_hoi)) {
                        newQuestions.push(question);
                    }
                });
            });
            
            // Thêm vào danh sách câu hỏi đã chọn
            selectedQuestions = [...selectedQuestions, ...newQuestions];
            
            // Cập nhật hiển thị
            updateSelectedQuestions();
            
            // Đóng modal
            addQuestionModal.style.display = 'none';
        });
    });
</script>
@endsection
