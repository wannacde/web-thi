@extends('layout.main')

@section('title', 'Chi tiết kết quả')

@section('content')
    <div class="result-header">
        <h1>Kết quả bài thi: {{ $result->baiThi->ten_bai_thi }}</h1>
        <div class="result-actions">
            <a href="{{ route('results.index') }}" class="btn-primary">Quay lại</a>
            <a href="{{ route('results.pdf', $result->ma_ket_qua) }}" class="btn-primary">Xuất PDF</a>
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
<style>
    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .result-actions {
        display: flex;
        gap: 0.5rem;
    }
    .result-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .info-section {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
    }
    .info-item {
        margin-bottom: 0.5rem;
    }
    .score-display {
        text-align: center;
        margin-bottom: 1rem;
    }
    .score {
        font-size: 3rem;
        font-weight: bold;
        color: #3490dc;
    }
    .score-label {
        font-size: 1.2rem;
        color: #666;
    }
    .stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .stat-item {
        text-align: center;
        background-color: #fff;
        padding: 1rem;
        border-radius: 8px;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .stat-label {
        color: #666;
    }
    .answers-review {
        margin-top: 2rem;
    }
    .question-review {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 5px solid #ccc;
    }
    .question-review.correct {
        border-left-color: #28a745;
    }
    .question-review.incorrect {
        border-left-color: #dc3545;
    }
    .answer-details {
        margin-top: 1rem;
    }
    .user-answer, .correct-answer {
        margin-bottom: 0.5rem;
    }
    .correct-text {
        color: #28a745;
    }
    .incorrect-text {
        color: #dc3545;
    }
    
    @media (max-width: 768px) {
        .result-info {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection