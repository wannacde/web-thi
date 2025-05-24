@extends('layout.main')

@section('title', 'Thêm câu hỏi mới')

@section('content')
    <h1>Thêm câu hỏi mới</h1>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Thêm meta csrf-token để JS lấy được token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <form method="POST" action="{{ route('questions.store') }}" id="createQuestionForm">
        @csrf
        
        <div class="form-group">
            <label for="ma_mon_hoc">Môn học</label>
            <select id="ma_mon_hoc" name="ma_mon_hoc" required>
                <option value="">-- Chọn môn học --</option>
                @foreach($monHocs as $monHoc)
                    <option value="{{ $monHoc->ma_mon_hoc }}" {{ old('ma_mon_hoc') == $monHoc->ma_mon_hoc ? 'selected' : '' }}>
                        {{ $monHoc->ten_mon_hoc }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="ma_chuong">Chương</label>
            <select id="ma_chuong" name="ma_chuong" required>
                <option value="">-- Chọn chương --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="noi_dung">Nội dung câu hỏi</label>
            <textarea id="noi_dung" name="noi_dung" rows="4" required>{{ old('noi_dung') }}</textarea>
        </div>

        <div class="form-group">
            <label for="loai_cau_hoi">Loại câu hỏi</label>
            <select id="loai_cau_hoi" name="loai_cau_hoi" required>
                <option value="trac_nghiem" {{ old('loai_cau_hoi') == 'trac_nghiem' ? 'selected' : '' }}>Trắc nghiệm</option>
                <option value="dien_khuyet" {{ old('loai_cau_hoi') == 'dien_khuyet' ? 'selected' : '' }}>Điền khuyết</option>
            </select>
        </div>

        <div id="answers-container">
            <h3>Đáp án</h3>
            
            <div id="trac-nghiem-answers" class="answer-type-container">
                <div class="answer-item">
                    <div class="form-group">
                        <label>Đáp án 1</label>
                        <div class="answer-input-group">
                            <input type="text" name="dap_an[0][noi_dung]" placeholder="Nội dung đáp án" required>
                            <div class="radio-group">
                                <input type="radio" name="correct_answer" value="0" checked onchange="updateDungSai(this)">
                                <label>Đáp án đúng</label>
                            </div>
                        </div>
                        <input type="hidden" name="dap_an[0][dung_sai]" value="1" id="dung_sai_0">
                    </div>
                </div>
                <div class="answer-item">
                    <div class="form-group">
                        <label>Đáp án 2</label>
                        <div class="answer-input-group">
                            <input type="text" name="dap_an[1][noi_dung]" placeholder="Nội dung đáp án" required>
                            <div class="radio-group">
                                <input type="radio" name="correct_answer" value="1" onchange="updateDungSai(this)">
                                <label>Đáp án đúng</label>
                            </div>
                        </div>
                        <input type="hidden" name="dap_an[1][dung_sai]" value="0" id="dung_sai_1">
                    </div>
                </div>
                <div id="more-answers"></div>
                <button type="button" id="add-answer" class="btn-secondary">Thêm đáp án</button>
            </div>
            <div id="dien-khuyet-answers" class="answer-type-container" style="display: none;">
                <div class="form-group">
                    <label>Đáp án đúng</label>
                    <input type="text" name="dap_an[0][noi_dung]" placeholder="Nhập đáp án đúng" required>
                    <input type="hidden" name="dap_an[0][dung_sai]" value="1">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <input type="submit" class="btn-primary" value="Thêm câu hỏi">
            <a href="{{ route('questions.index') }}" class="btn-secondary">Hủy</a> 
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
    .form-group label:before {
        content: '\f044';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .btn-primary i {
        margin-right: 0.5rem;
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
    .answer-input-group {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .answer-input-group input[type="text"] {
        flex-grow: 1;
    }
    .radio-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .radio-group input[type="radio"] {
        width: auto;
        margin: 0;
    }
    .answer-item {
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('scripts')
<script>
    function updateDungSai(radio) {
        // Reset all to 0
        for (let i = 0; i < 10; i++) {
            const input = document.getElementById('dung_sai_' + i);
            if (input) input.value = '0';
        }
        
        // Set selected to 1
        const selectedIndex = radio.value;
        const selectedInput = document.getElementById('dung_sai_' + selectedIndex);
        if (selectedInput) selectedInput.value = '1';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('createQuestionForm');
        if (!form) {
            return;
        }
        const monHocSelect = document.getElementById('ma_mon_hoc');
        const chuongSelect = document.getElementById('ma_chuong');
        const loaiCauHoiSelect = document.getElementById('loai_cau_hoi');
        const tracNghiemContainer = document.getElementById('trac-nghiem-answers');
        const dienKhuyetContainer = document.getElementById('dien-khuyet-answers');
        const addAnswerBtn = document.getElementById('add-answer');
        const moreAnswersContainer = document.getElementById('more-answers');
        // Lấy CSRF token an toàn hơn
        let csrfToken = '';
        const metaCsrf = document.querySelector('meta[name="csrf-token"]');
        if (metaCsrf) {
            csrfToken = metaCsrf.getAttribute('content');
        }
        
        let answerCount = 2;
        
        // Handle subject change
        monHocSelect.addEventListener('change', function() {
            const monHocId = this.value;
            chuongSelect.innerHTML = '<option value="">-- Chọn chương --</option>';
            chuongSelect.disabled = !monHocId;
            if (!monHocId) return;
            fetch(`/chuong/${monHocId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    const html = await response.text();
                    throw new Error('Server trả về không phải JSON');
                }
            })
            .then(chuongs => {
                if (!Array.isArray(chuongs)) return;
                chuongs.forEach(chuong => {
                    const option = document.createElement('option');
                    option.value = chuong.ma_chuong;
                    option.textContent = chuong.ten_chuong;
                    chuongSelect.appendChild(option);
                });
                chuongSelect.disabled = false;
            })
            .catch(error => {
                alert('Lỗi khi tải chương: ' + error.message);
                chuongSelect.disabled = true;
            });
        });
        
        loaiCauHoiSelect.addEventListener('change', function() {
            tracNghiemContainer.style.display = this.value === 'trac_nghiem' ? 'block' : 'none';
            dienKhuyetContainer.style.display = this.value === 'dien_khuyet' ? 'block' : 'none';

            // Xóa các input đáp án không cần thiết khi chuyển loại
            if (this.value === 'trac_nghiem') {
                dienKhuyetContainer.querySelectorAll('input').forEach(input => input.disabled = true);
                tracNghiemContainer.querySelectorAll('input').forEach(input => input.disabled = false);
            } else {
                tracNghiemContainer.querySelectorAll('input').forEach(input => input.disabled = true);
                dienKhuyetContainer.querySelectorAll('input').forEach(input => input.disabled = false);
            }
        });

        // Add new answer
        addAnswerBtn.addEventListener('click', function() {
            const answerItem = document.createElement('div');
            answerItem.className = 'answer-item';
            answerItem.innerHTML = `
                <div class="form-group">
                    <label>Đáp án ${answerCount + 1}</label>
                    <div class="answer-input-group">
                        <input type="text" name="dap_an[${answerCount}][noi_dung]" placeholder="Nội dung đáp án" required>
                        <div class="radio-group">
                            <input type="radio" name="correct_answer" value="${answerCount}" onchange="updateDungSai(this)">
                            <label>Đáp án đúng</label>
                        </div>
                    </div>
                    <input type="hidden" name="dap_an[${answerCount}][dung_sai]" value="0" id="dung_sai_${answerCount}">
                </div>
            `;
            moreAnswersContainer.appendChild(answerItem);
            answerCount++;
        });
        
        // Handle form submit - đơn giản hóa
        form.addEventListener('submit', function(e) {
            // Chỉ kiểm tra required của form
            if (!form.checkValidity()) {
                e.preventDefault();
                form.reportValidity();
                return;
            }
        });
    });
    
    // Khi load trang, trigger sự kiện change để render đúng input đáp án
    document.addEventListener('DOMContentLoaded', function() {
        const loaiCauHoiSelect = document.getElementById('loai_cau_hoi');
        if (loaiCauHoiSelect) {
            loaiCauHoiSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
