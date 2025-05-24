@extends('layout.main')

@section('title', 'Làm bài: ' . $exam->ten_bai_thi)

@section('content')
    <div class="exam-header">
        <h1>{{ $exam->ten_bai_thi }}</h1>
        <div class="exam-timer">
            <div id="timer" data-time="{{ $exam->thoi_gian * 60 }}">
                <i class="fas fa-hourglass-half timer-icon"></i> Thời gian còn lại: <span id="minutes">{{ $exam->thoi_gian }}</span>:<span id="seconds">00</span>
            </div>
        </div>
    </div>

    <div class="exam-info">
        <div class="info-item">
            <strong>Môn học:</strong> {{ $exam->monHoc->ten_mon_hoc }}
        </div>
        <div class="info-item">
            <strong>Số câu hỏi:</strong> {{ count($exam->cauHoi) }}
        </div>
        <div class="info-item">
            <strong>Thời gian:</strong> {{ $exam->thoi_gian }} phút
        </div>
    </div>

    <form id="examForm" method="POST" action="{{ route('exams.submit', $exam->slug) }}">
        @csrf
        
        <div class="questions-container">
            @if(count($exam->cauHoi) > 0)
                @foreach($exam->cauHoi as $index => $cauHoi)
                <div class="question-item" id="question-{{ $cauHoi->ma_cau_hoi }}" data-number="{{ $index + 1 }}">
                        <h3>Câu {{ $index + 1 }}: {{ $cauHoi->noi_dung }}</h3>
                        
                        @if($cauHoi->loai_cau_hoi == 'trac_nghiem')
                            <div class="answers">
                                @foreach($cauHoi->dapAn as $dapAn)
                                    <div class="answer-option">
                                        <input type="radio" 
                                               id="answer-{{ $cauHoi->ma_cau_hoi }}-{{ $dapAn->ma_dap_an }}" 
                                               name="question[{{ $cauHoi->ma_cau_hoi }}]" 
                                               value="{{ $dapAn->ma_dap_an }}">
                                        <label for="answer-{{ $cauHoi->ma_cau_hoi }}-{{ $dapAn->ma_dap_an }}">
                                            {{ $dapAn->noi_dung }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="fill-blank">
                                <input type="text" 
                                       name="question[{{ $cauHoi->ma_cau_hoi }}]" 
                                       placeholder="Nhập câu trả lời của bạn">
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <p>Không có câu hỏi nào trong bài thi này.</p>
            @endif
        </div>
        
        <div class="exam-navigation">
            <div class="question-nav">
                @foreach($exam->cauHoi as $index => $cauHoi)
                    <a href="#question-{{ $cauHoi->ma_cau_hoi }}" class="question-number" data-question="{{ $cauHoi->ma_cau_hoi }}">
                        {{ $index + 1 }}
                    </a>
                @endforeach
            </div>
        </div>
        
        <div class="submit-section">
            <button type="submit" class="btn-primary">Nộp bài</button>
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
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
    }
    .exam-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .exam-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #3730a3;
        position: relative;
        padding-bottom: 0.8rem;
    }
    .exam-header h1:before {
        content: '\f5cb';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.8rem;
        color: #6366f1;
    }
    .exam-header h1:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 4px;
    }
    .exam-timer {
        background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
        color: #333;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(247,151,30,0.15);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .timer-icon {
        font-size: 1.3rem;
        margin-right: 0.5rem;
        animation: pulse 1.5s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    @keyframes pulse-warning {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.9; }
        100% { transform: scale(1); opacity: 1; }
    }
    @keyframes pulse-danger {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }
    .exam-info {
        background: linear-gradient(120deg, #fff 60%, #e0e7ff 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        border-left: 5px solid #6366f1;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    .info-item {
        padding: 0.5rem 0;
        display: flex;
        align-items: center;
    }
    .info-item strong {
        color: #3730a3;
        margin-right: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }
    
    .info-item strong:before {
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: #6366f1;
    }
    .info-item:nth-child(1) strong:before {
        content: '\f02d';
    }
    .info-item:nth-child(2) strong:before {
        content: '\f059';
    }
    .info-item:nth-child(3) strong:before {
        content: '\f017';
    }
    .question-item {
        border-left: 4px solid #6366f1;
        background: linear-gradient(120deg, #fff 60%, #f0f4ff 100%);
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        padding: 1.5rem 1.8rem;
        border-radius: 12px;
        position: relative;
        transition: all 0.3s ease;
    }
    .question-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(99,102,241,0.18);
    }
    .question-item h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #3730a3;
        margin-bottom: 1.2rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .question-item:before {
        content: attr(data-number);
        position: absolute;
        top: -10px;
        left: -10px;
        background: #6366f1;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        box-shadow: 0 2px 8px rgba(99,102,241,0.2);
    }
    .answers {
        margin-top: 1.5rem;
    }
    .answer-option {
        margin-bottom: 1rem;
        padding: 0.8rem 1.2rem;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .answer-option:hover {
        background: #f8fafc;
        border-color: #c7d2fe;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
    }
    .answer-option input[type="radio"] {
        display: none;
    }
    .answer-option label {
        display: flex;
        align-items: center;
        cursor: pointer;
        width: 100%;
        font-size: 1.05rem;
    }
    .answer-option label:before {
        content: '\f111';
        font-family: 'Font Awesome 6 Free';
        font-weight: 400;
        margin-right: 0.8rem;
        color: #6366f1;
        font-size: 1.2rem;
        min-width: 20px;
    }
    .answer-option input[type="radio"]:checked + label {
        color: #3730a3;
        font-weight: 500;
    }
    .answer-option input[type="radio"]:checked + label:before {
        content: '\f058';
        color: #6366f1;
        font-weight: 900;
    }
    .answer-option input[type="radio"]:checked ~ .answer-option {
        background: #f0f4ff;
        border-color: #6366f1;
    }
    .fill-blank {
        margin-top: 1.5rem;
    }
    .fill-blank input {
        width: 100%;
        padding: 1rem 1.2rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1.05rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(99,102,241,0.05);
    }
    .fill-blank input:focus {
        border-color: #6366f1;
        box-shadow: 0 4px 12px rgba(99,102,241,0.1);
        outline: none;
    }
    .fill-blank input::placeholder {
        color: #a0aec0;
    }
    .exam-navigation {
        position: sticky;
        bottom: 0;
        background: rgba(255, 255, 255, 0.95);
        padding: 1.2rem;
        border-top: 1px solid #e2e8f0;
        margin-top: 2rem;
        backdrop-filter: blur(8px);
        box-shadow: 0 -4px 16px rgba(99,102,241,0.08);
        border-radius: 12px 12px 0 0;
        z-index: 100;
    }
    .question-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .question-number {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        font-weight: 600;
        background-color: #f1f5f9;
        border-radius: 50%;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .question-number:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(99,102,241,0.15);
        background-color: #e2e8f0;
    }
    .question-number.answered {
        background: linear-gradient(135deg, #10b981, #34d399);
        color: white;
        box-shadow: 0 4px 12px rgba(16,185,129,0.2);
    }
    .submit-section {
        text-align: center;
        margin-top: 2rem;
    }
    .btn-primary {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.8rem 2.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.15);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #60a5fa 0%, #6366f1 100%);
        box-shadow: 0 6px 20px rgba(99,102,241,0.25);
        transform: translateY(-2px) scale(1.03);
    }
    .btn-primary:before {
        content: '\f0c7';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Timer functionality
        const timerElement = document.getElementById('timer');
        const minutesElement = document.getElementById('minutes');
        const secondsElement = document.getElementById('seconds');
        
        let totalSeconds = parseInt(timerElement.dataset.time);
        
        const timer = setInterval(function() {
            totalSeconds--;
            
            if (totalSeconds <= 0) {
                clearInterval(timer);
                document.getElementById('examForm').submit();
                return;
            }
            
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            
            minutesElement.textContent = minutes.toString().padStart(2, '0');
            secondsElement.textContent = seconds.toString().padStart(2, '0');
            
            // Change color when time is running out
            if (totalSeconds < 300) { // Less than 5 minutes
                timerElement.style.background = 'linear-gradient(90deg, #ef4444 0%, #f87171 100%)';
                timerElement.style.color = 'white';
                timerElement.style.animation = 'pulse-warning 1s infinite';
                document.querySelector('.timer-icon').style.animation = 'pulse-warning 1s infinite';
            }
            
            // Add warning animation
            if (totalSeconds < 60) { // Less than 1 minute
                timerElement.style.animation = 'pulse-danger 0.5s infinite';
                document.querySelector('.timer-icon').style.animation = 'pulse-danger 0.5s infinite';
            }
        }, 1000);
        
        // Track answered questions
        const radioInputs = document.querySelectorAll('input[type="radio"]');
        const textInputs = document.querySelectorAll('.fill-blank input');
        const questionNumbers = document.querySelectorAll('.question-number');
        
        function markAnswered(questionId) {
            questionNumbers.forEach(item => {
                if (item.dataset.question === questionId) {
                    item.classList.add('answered');
                }
            });
        }
        
        radioInputs.forEach(input => {
            input.addEventListener('change', function() {
                const questionId = this.name.match(/\d+/)[0];
                markAnswered(questionId);
            });
        });
        
        textInputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    const questionId = this.name.match(/\d+/)[0];
                    markAnswered(questionId);
                }
            });
        });
        
        // Confirm before leaving page
        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '';
        });
        
        // Submit form
        document.getElementById('examForm').addEventListener('submit', function() {
            window.removeEventListener('beforeunload', function() {});
        });
    });
</script>
@endsection