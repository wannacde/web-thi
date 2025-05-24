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
                                <input type="radio" name="correct_answer" value="0" checked>
                                <label>Đáp án đúng</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="answer-item">
                    <div class="form-group">
                        <label>Đáp án 2</label>
                        <div class="answer-input-group">
                            <input type="text" name="dap_an[1][noi_dung]" placeholder="Nội dung đáp án" required>
                            <div class="radio-group">
                                <input type="radio" name="correct_answer" value="1">
                                <label>Đáp án đúng</label>
                            </div>
                        </div>
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
<style>
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
        let submitting = false;
        
        // Handle subject change
        monHocSelect.addEventListener('change', function() {
            const monHocId = this.value;
            chuongSelect.innerHTML = '<option value="">-- Chọn chương --</option>';
            chuongSelect.disabled = !monHocId;
            if (!monHocId) return;
            fetch(`/chuong/${monHocId}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.indexOf('application/json') !== -1) {
                    return response.json();
                } else {
                    return response.text().then(text => { throw new Error('Server trả về không phải JSON: ' + text.substring(0, 200)); });
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
                if (error.message.includes('Phiên đăng nhập đã hết hạn') || error.message.includes('Server trả về không phải JSON')) {
                    // Xóa thông báo cũ nếu có
                    const oldErrorDiv = document.getElementById('sessionErrorDiv');
                    if (oldErrorDiv) oldErrorDiv.remove();
                    // Hiển thị thông báo đẹp với nút reload
                    const errorDiv = document.createElement('div');
                    errorDiv.id = 'sessionErrorDiv';
                    errorDiv.style.background = '#f8d7da';
                    errorDiv.style.color = '#721c24';
                    errorDiv.style.padding = '1rem';
                    errorDiv.style.borderRadius = '4px';
                    errorDiv.style.margin = '1rem 0';
                    errorDiv.innerHTML = `
                        <strong>Phiên đăng nhập đã hết hạn hoặc có lỗi máy chủ.</strong><br>
                        <button id="reloadPageBtn" style="margin-top:8px;padding:6px 16px;background:#3490dc;color:#fff;border:none;border-radius:4px;cursor:pointer;">Tải lại trang</button>
                    `;
                    form.style.display = 'none';
                    form.parentNode.insertBefore(errorDiv, form);
                    document.getElementById('reloadPageBtn').onclick = function() { location.reload(); };
                } else {
                    alert('Lỗi khi tải chương: ' + error.message);
                }
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
            const checkedAttr = tracNghiemContainer.querySelector('input[type="radio"][name="correct_answer"]:checked') ? '' : 'checked';
            const answerItem = document.createElement('div');
            answerItem.className = 'answer-item';
            answerItem.innerHTML = `
                <div class="form-group">
                    <label>Đáp án ${answerCount + 1}</label>
                    <div class="answer-input-group">
                        <input type="text" name="dap_an[${answerCount}][noi_dung]" placeholder="Nội dung đáp án" required>
                        <div class="radio-group">
                            <input type="radio" name="correct_answer" value="${answerCount}" ${checkedAttr}>
                            <label>Đáp án đúng</label>
                        </div>
                    </div>
                </div>
            `;
            moreAnswersContainer.appendChild(answerItem);
            answerCount++;
        });
        
        // Handle form submit
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (submitting) return;
            submitting = true;

            if (loaiCauHoiSelect.value.trim() === 'trac_nghiem') {
                // Đảm bảo luôn có radio được chọn khi submit
                let radios = tracNghiemContainer.querySelectorAll('input[type="radio"][name="correct_answer"]');
                if (radios.length > 0 && !tracNghiemContainer.querySelector('input[type="radio"][name="correct_answer"]:checked')) {
                    radios[0].checked = true;
                }
                // Kiểm tra required của form
                if (!form.checkValidity()) {
                    form.reportValidity();
                    submitting = false;
                    return;
                }
                const correctAnswer = document.querySelector('input[name="correct_answer"]:checked');
                if (!correctAnswer) {
                    alert('Vui lòng chọn đáp án đúng!');
                    submitting = false;
                    return;
                }
                document.querySelectorAll('input[name$="[dung_sai]"]')?.forEach(input => input.remove());
                const answers = document.querySelectorAll('input[type="text"][name^="dap_an["][name$="][noi_dung]"]');
                answers.forEach((answer) => {
                    const match = answer.name.match(/dap_an\[(\d+)\]\[noi_dung\]/);
                    if (!match) return;
                    const idx = match[1];
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `dap_an[${idx}][dung_sai]`;
                    hiddenInput.value = (String(idx) === String(correctAnswer.value)) ? '1' : '0';
                    this.appendChild(hiddenInput);
                });
                this.submit();
            } else if (loaiCauHoiSelect.value.trim() === 'dien_khuyet') {
                // Kiểm tra required của form
                if (!form.checkValidity()) {
                    form.reportValidity();
                    submitting = false;
                    return;
                }
                this.submit();
            } else {
                // fallback: just submit
                this.submit();
            }
        });
    });
    // Khi load trang, trigger sự kiện change để render đúng input đáp án
    loaiCauHoiSelect.dispatchEvent(new Event('change'));
</script>
@endsection