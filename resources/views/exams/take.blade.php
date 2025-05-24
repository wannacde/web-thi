@extends('layout.main')

@section('title', 'Làm bài: ' . $exam->ten_bai_thi)

@section('content')
    <div class="exam-header">
        <h1>{{ $exam->ten_bai_thi }}</h1>
        <div class="exam-timer">
            <div id="timer" data-time="{{ $exam->thoi_gian * 60 }}">
                Thời gian còn lại: <span id="minutes">{{ $exam->thoi_gian }}</span>:<span id="seconds">00</span>
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
                    <div class="question-item" id="question-{{ $cauHoi->ma_cau_hoi }}">
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
        background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
    }
    .exam-header h1:before {
        content: '\f02d';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .exam-timer {
        background: linear-gradient(90deg, #f7971e 0%, #ffd200 100%);
        color: #333;
        font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(247,151,30,0.08);
    }
    .exam-info {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        display: flex;
        gap: 2rem;
    }
    .question-item {
        border-left: 4px solid #3490dc;
        background: #fff;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(52,144,220,0.08);
        transition: box-shadow 0.3s;
    }
    .question-item:hover {
        box-shadow: 0 4px 16px rgba(52,144,220,0.18);
    }
    .answers {
        margin-top: 1rem;
    }
    .answer-option {
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }
    .answer-option input[type="radio"] {
        margin-right: 0.5rem;
        width: auto;
    }
    .answers label:before {
        content: '\f111';
        font-family: 'Font Awesome 6 Free';
        font-weight: 400;
        margin-right: 0.5rem;
        color: #6a82fb;
    }
    .answers input[type="radio"]:checked + label:before {
        content: '\f058';
        color: #3490dc;
        font-weight: 900;
    }
    .fill-blank input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .exam-navigation {
        position: sticky;
        bottom: 0;
        background-color: white;
        padding: 1rem 0;
        border-top: 1px solid #eee;
        margin-top: 2rem;
    }
    .question-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    .question-number {
        display: inline-block;
        width: 2rem;
        height: 2rem;
        line-height: 2rem;
        text-align: center;
        background-color: #f8f9fa;
        border-radius: 50%;
        color: #333;
        text-decoration: none;
    }
    .question-number.answered {
        background-color: #d4edda;
    }
    .submit-section {
        text-align: center;
        margin-top: 2rem;
    }
    .btn-primary {
        font-size: 1.1rem;
        padding: 0.75rem 2rem;
        border-radius: 6px;
        margin-top: 1.5rem;
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
                timerElement.style.backgroundColor = '#dc3545';
                timerElement.style.color = 'white';
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