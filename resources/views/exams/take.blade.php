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

    <form id="examForm" method="POST" action="{{ route('exam.submit', $exam->ma_bai_thi) }}">
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
<style>
    .exam-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .exam-timer {
        background-color: #f8d7da;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: bold;
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
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
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