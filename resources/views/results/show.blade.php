@extends('layout.main')

@section('title', 'Chi tiết kết quả')

@section('content')
    <div class="result-header">
        <h1>Kết quả bài thi: {{ $result->baiThi->ten_bai_thi }}</h1>
        <div class="result-actions">
            <a href="{{ route('results.index') }}" class="btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
        </div>
    </div>

    <div class="result-info">
        <div class="info-section">
            <h2>Thông tin bài thi</h2>
            <div class="info-item">
                <strong>Tên bài thi:</strong> {{ $result->baiThi->ten_bai_thi }}
            </div>
            <div class="info-item">
                <strong>Môn học:</strong> {{ $result->baiThi->monHoc->ten_mon_hoc }}
            </div>
            <div class="info-item">
                <strong>Thời gian làm bài:</strong> {{ $result->baiThi->thoi_gian }} phút
            </div>
            <div class="info-item">
                <strong>Ngày nộp:</strong> {{ \Carbon\Carbon::parse($result->ngay_nop)->format('d/m/Y H:i') }}
            </div>
        </div>
        
        <div class="info-section">
            <h2>Kết quả</h2>
            <div class="score-display">
                <div class="score">{{ number_format($result->diem, 1) }}</div>
                <div class="score-label">Điểm</div>
            </div>
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $correctAnswers }}</div>
                    <div class="stat-label">Câu đúng</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $wrongAnswers }}</div>
                    <div class="stat-label">Câu sai</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $totalQuestions }}</div>
                    <div class="stat-label">Tổng số câu</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $percentCorrect }}%</div>
                    <div class="stat-label">Tỷ lệ đúng</div>
                </div>
            </div>
        </div>
    </div>

    <div class="answers-review">
        <h2>Chi tiết câu trả lời</h2>
        
        @if(count($result->traLoi) > 0)
            @foreach($result->traLoi as $index => $traLoi)
                <div class="question-review {{ $traLoi->dung_sai ? 'correct' : 'incorrect' }}">
                    <h3>Câu {{ $index + 1 }}: {{ $traLoi->cauHoi->noi_dung }}</h3>
                    
                    <div class="answer-details">
                        @if($traLoi->cauHoi->loai_cau_hoi == 'trac_nghiem')
                            <div class="user-answer">
                                <strong>Câu trả lời của bạn:</strong>
                                @php
                                    $userAnswerText = 'Không chọn';
                                    $correctAnswerText = 'Không có đáp án đúng';
                                    
                                    foreach($traLoi->cauHoi->dapAn as $dapAn) {
                                        if($dapAn->ma_dap_an == $traLoi->dap_an_chon) {
                                            $userAnswerText = $dapAn->noi_dung;
                                        }
                                        
                                        if($dapAn->dung_sai) {
                                            $correctAnswerText = $dapAn->noi_dung;
                                        }
                                    }
                                @endphp
                                <span class="{{ $traLoi->dung_sai ? 'correct-text' : 'incorrect-text' }}">
                                    {{ $userAnswerText }}
                                </span>
                            </div>
                            
                            @if(!$traLoi->dung_sai)
                                <div class="correct-answer">
                                    <strong>Đáp án đúng:</strong>
                                    <span class="correct-text">{{ $correctAnswerText }}</span>
                                </div>
                            @endif
                        @else
                            <div class="user-answer">
                                <strong>Câu trả lời của bạn:</strong>
                                <span class="{{ $traLoi->dung_sai ? 'correct-text' : 'incorrect-text' }}">
                                    {{ $traLoi->dap_an_chon ?: 'Không trả lời' }}
                                </span>
                            </div>
                            
                            @if(!$traLoi->dung_sai)
                                <div class="correct-answer">
                                    <strong>Đáp án đúng:</strong>
                                    <span class="correct-text">
                                        @foreach($traLoi->cauHoi->dapAn as $dapAn)
                                            @if($dapAn->dung_sai)
                                                {{ $dapAn->noi_dung }}
                                            @endif
                                        @endforeach
                                    </span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <p>Không có thông tin chi tiết về câu trả lời.</p>
        @endif
    </div>
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
    }
    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem 0;
    }
    .result-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #3730a3;
        position: relative;
        padding-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .result-header h1:before {
        content: '\f559';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: #6366f1;
    }
    .result-header h1:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 4px;
    }
    .result-actions {
        display: flex;
        gap: 1rem;
    }
    .btn-primary {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #60a5fa 0%, #6366f1 100%);
        box-shadow: 0 4px 16px rgba(99,102,241,0.15);
        transform: translateY(-2px) scale(1.03);
    }
    .result-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    .info-section {
        background: linear-gradient(120deg, #fff 60%, #e0e7ff 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        border-left: 5px solid #6366f1;
    }
    .info-section h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #3730a3;
        margin-bottom: 1.2rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #e2e8f0;
        position: relative;
    }
    .info-section h2:after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 3px;
    }
    .info-item {
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
    }
    .info-item strong {
        min-width: 150px;
        color: #6366f1;
        font-weight: 600;
    }
    .score-display {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .score-display .score {
        font-size: 3.5rem;
        font-weight: 700;
        color: #6366f1;
        text-shadow: 0 2px 4px rgba(99,102,241,0.2);
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }
    .score-display .score-label {
        font-size: 1.2rem;
        color: #4b5563;
        font-weight: 500;
    }
    .stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .stat-item {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        transition: all 0.3s ease;
    }
    .stat-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(99,102,241,0.12);
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #6366f1;
        margin-bottom: 0.3rem;
    }
    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 500;
    }
    .answers-review {
        margin-top: 2rem;
    }
    .answers-review h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #3730a3;
        margin-bottom: 1.5rem;
        padding-bottom: 0.8rem;
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .answers-review h2:before {
        content: '\f02d';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: #6366f1;
    }
    .answers-review h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 4px;
    }
    .question-review {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .question-review:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(99,102,241,0.12);
    }
    .question-review.correct {
        border-left: 5px solid #10b981;
    }
    .question-review.incorrect {
        border-left: 5px solid #ef4444;
    }
    .question-review h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #3730a3;
        margin-bottom: 1rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #e2e8f0;
    }
    .answer-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    .user-answer, .correct-answer {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
    }
    .user-answer strong, .correct-answer strong {
        display: block;
        margin-bottom: 0.5rem;
        color: #4b5563;
        font-weight: 600;
    }
    .correct-text {
        color: #10b981;
        font-weight: 600;
    }
    .incorrect-text {
        color: #ef4444;
        font-weight: 600;
    }
    .question-review.correct:before {
        content: '\f058';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: 1rem;
        right: 1rem;
        color: #10b981;
        font-size: 1.5rem;
        opacity: 0.2;
    }
    .question-review.incorrect:before {
        content: '\f057';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        top: 1rem;
        right: 1rem;
        color: #ef4444;
        font-size: 1.5rem;
        opacity: 0.2;
    }
    @media (max-width: 768px) {
        .result-info {
            grid-template-columns: 1fr;
        }
        .result-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>
@endsection