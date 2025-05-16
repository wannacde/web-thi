@extends('layout.main')

@section('title', 'Chỉnh sửa câu hỏi')

@section('content')
    <h1>Chỉnh sửa câu hỏi</h1>
    
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('questions.update', $question->ma_cau_hoi) }}" id="editQuestionForm">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="ma_mon_hoc">Môn học</label>
            <select id="ma_mon_hoc" name="ma_mon_hoc" required>
                <option value="">-- Chọn môn học --</option>
                @foreach($monHocs as $monHoc)
                    <option value="{{ $monHoc->ma_mon_hoc }}" {{ $question->chuong->ma_mon_hoc == $monHoc->ma_mon_hoc ? 'selected' : '' }}>
                        {{ $monHoc->ten_mon_hoc }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="ma_chuong">Chương</label>
            <select id="ma_chuong" name="ma_chuong" required>
                <option value="">-- Chọn chương --</option>
                @foreach($chuongs as $chuong)
                    @if($chuong->ma_mon_hoc == $question->chuong->ma_mon_hoc)
                        <option value="{{ $chuong->ma_chuong }}" {{ $question->ma_chuong == $chuong->ma_chuong ? 'selected' : '' }}>
                            {{ $chuong->ten_chuong }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="noi_dung">Nội dung câu hỏi</label>
            <textarea id="noi_dung" name="noi_dung" rows="4" required>{{ $question->noi_dung }}</textarea>
        </div>

        <div class="form-group">
            <label for="loai_cau_hoi">Loại câu hỏi</label>
            <select id="loai_cau_hoi" name="loai_cau_hoi" required>
                <option value="trac_nghiem" {{ $question->loai_cau_hoi == 'trac_nghiem' ? 'selected' : '' }}>Trắc nghiệm</option>
                <option value="dien_khuyet" {{ $question->loai_cau_hoi == 'dien_khuyet' ? 'selected' : '' }}>Điền khuyết</option>
            </select>
        </div>

        <div id="answers-container">
            <h3>Đáp án</h3>
            
            <div id="trac-nghiem-answers" class="answer-type-container" {{ $question->loai_cau_hoi != 'trac_nghiem' ? 'style=display:none' : '' }}>
                @php $answerCount = 0; @endphp
                @foreach($question->dapAn as $index => $dapAn)
                    <div class="answer-item">
                        <div class="form-group">
                            <label>Đáp án {{ $index + 1 }}</label>
                            <div class="answer-input-group">
                                <input type="text" name="dap_an[{{ $index }}][noi_dung]" value="{{ $dapAn->noi_dung }}" placeholder="Nội dung đáp án" required>
                                <div class="radio-group">
                                    <input type="radio" name="correct_answer" value="{{ $index }}" {{ $dapAn->dung_sai ? 'checked' : '' }}>
                                    <label>Đáp án đúng</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $answerCount++; @endphp
                @endforeach
                
                <div id="more-answers"></div>
                
                <button type="button" id="add-answer" class="btn-secondary">Thêm đáp án</button>
            </div>
            
            <div id="dien-khuyet-answers" class="answer-type-container" {{ $question->loai_cau_hoi != 'dien_khuyet' ? 'style=display:none' : '' }}>
                <div class="form-group">
                    <label>Đáp án đúng</label>
                    <input type="text" name="dap_an_dien_khuyet" value="{{ $question->loai_cau_hoi == 'dien_khuyet' ? $question->dapAn->where('dung_sai', 1)->first()->noi_dung : '' }}" placeholder="Nhập đáp án đúng">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Cập nhật câu hỏi</button>
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
        const monHocSelect = document.getElementById('ma_mon_hoc');
        const chuongSelect = document.getElementById('ma_chuong');
        const loaiCauHoiSelect = document.getElementById('loai_cau_hoi');
        const tracNghiemContainer = document.getElementById('trac-nghiem-answers');
        const dienKhuyetContainer = document.getElementById('dien-khuyet-answers');
        const addAnswerBtn = document.getElementById('add-answer');
        const moreAnswersContainer = document.getElementById('more-answers');
        
        let answerCount = {{ $question->dapAn->count() }}; // Số đáp án hiện tại
        
        // Xử lý thay đổi môn học
        monHocSelect.addEventListener('change', function() {
            const monHocId = this.value;
            
            // Reset chương select
            chuongSelect.innerHTML = '<option value="">-- Chọn chương --</option>';
            
            if (!monHocId) return;
            
            // Lấy danh sách chương theo môn học
            fetch(`/chuong/${monHocId}`)
                .then(response => response.json())
                .then(chuongs => {
                    chuongs.forEach(chuong => {
                        const option = document.createElement('option');
                        option.value = chuong.ma_chuong;
                        option.textContent = chuong.ten_chuong;
                        chuongSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching chapters:', error));
        });
        
        // Xử lý thay đổi loại câu hỏi
        loaiCauHoiSelect.addEventListener('change', function() {
            if (this.value === 'trac_nghiem') {
                tracNghiemContainer.style.display = 'block';
                dienKhuyetContainer.style.display = 'none';
            } else {
                tracNghiemContainer.style.display = 'none';
                dienKhuyetContainer.style.display = 'block';
            }
        });
        
        // Thêm đáp án mới
        addAnswerBtn.addEventListener('click', function() {
            const answerItem = document.createElement('div');
            answerItem.className = 'answer-item';
            answerItem.innerHTML = `
                <div class="form-group">
                    <label>Đáp án ${answerCount + 1}</label>
                    <div class="answer-input-group">
                        <input type="text" name="dap_an[${answerCount}][noi_dung]" placeholder="Nội dung đáp án" required>
                        <div class="radio-group">
                            <input type="radio" name="correct_answer" value="${answerCount}">
                            <label>Đáp án đúng</label>
                        </div>
                    </div>
                </div>
            `;
            moreAnswersContainer.appendChild(answerItem);
            answerCount++;
        });
        
        // Xử lý submit form
        document.getElementById('editQuestionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Xử lý đáp án trắc nghiệm
            if (loaiCauHoiSelect.value === 'trac_nghiem') {
                const correctAnswerIndex = document.querySelector('input[name="correct_answer"]:checked').value;
                
                // Tạo mảng đáp án với đánh dấu đáp án đúng
                for (let i = 0; i < answerCount; i++) {
                    const isCorrect = (i.toString() === correctAnswerIndex);
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `dap_an[${i}][dung_sai]`;
                    hiddenInput.value = isCorrect ? '1' : '0';
                    this.appendChild(hiddenInput);
                }
            } else {
                // Xử lý đáp án điền khuyết
                const dienKhuyetAnswer = document.querySelector('input[name="dap_an_dien_khuyet"]').value;
                
                // Tạo input ẩn cho đáp án điền khuyết
                const hiddenInput1 = document.createElement('input');
                hiddenInput1.type = 'hidden';
                hiddenInput1.name = 'dap_an[0][noi_dung]';
                hiddenInput1.value = dienKhuyetAnswer;
                this.appendChild(hiddenInput1);
                
                const hiddenInput2 = document.createElement('input');
                hiddenInput2.type = 'hidden';
                hiddenInput2.name = 'dap_an[0][dung_sai]';
                hiddenInput2.value = '1';
                this.appendChild(hiddenInput2);
            }
            
            this.submit();
        });
    });
</script>
@endsection