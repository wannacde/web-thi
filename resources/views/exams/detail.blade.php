@extends('layout.main')

@section('title', $exam->ten_bai_thi)

@section('content')
    <div class="exam-header">
        <h1>{{ $exam->ten_bai_thi }}</h1>
        <div class="exam-actions">
            @if(Auth::user()->vai_tro != 'hoc_sinh' && 
                (Auth::user()->vai_tro == 'quan_tri' || Auth::user()->ma_nguoi_dung == $exam->nguoi_tao))
                <a href="{{ route('exams.edit', $exam->slug) }}" class="btn-action btn-primary"><i class="fas fa-edit"></i> Sửa</a>
                <form action="{{ route('exams.destroy', $exam->slug) }}" method="POST" class="action-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bài thi này?')"><i class="fas fa-trash"></i> Xóa</button>
                </form>
            @endif
        </div>
    </div>

    <div class="exam-info">
        <div class="info-item">
            <strong><i class="fas fa-book"></i> Môn học:</strong> {{ $exam->monHoc->ten_mon_hoc }}
        </div>
        <div class="info-item">
            <strong><i class="fas fa-question-circle"></i> Số câu hỏi:</strong> {{ $exam->tong_so_cau }} câu
        </div>
        <div class="info-item">
            <strong><i class="fas fa-clock"></i> Thời gian:</strong> {{ $exam->thoi_gian }} phút
        </div>
        <div class="info-item">
            <strong><i class="fas fa-user"></i> Người tạo:</strong> 
            @if(isset($exam->nguoiTao))
                {{ $exam->nguoiTao->ho_ten }}
            @else
                Không xác định
            @endif
        </div>
        <div class="info-item">
            <strong><i class="fas fa-calendar-alt"></i> Ngày tạo:</strong> {{ \Carbon\Carbon::parse($exam->ngay_tao)->format('d/m/Y H:i') }}
        </div>
    </div>

    @if(Auth::user()->vai_tro != 'hoc_sinh')
        <div class="exam-questions">
            <h2>Danh sách câu hỏi</h2>
            
            @if(count($exam->cauHoi) > 0)
                <ol class="questions-list">
                    @foreach($exam->cauHoi as $cauHoi)
                        <li>
                            <div class="question-content">
                                <h3>{{ $cauHoi->noi_dung }}</h3>
                                <p><strong>Loại câu hỏi:</strong> 
                                    @if($cauHoi->loai_cau_hoi == 'trac_nghiem')
                                        Trắc nghiệm
                                    @else
                                        Điền khuyết
                                    @endif
                                </p>
                                
                                @if(count($cauHoi->dapAn) > 0)
                                    <div class="answers">
                                        <strong>Đáp án:</strong>
                                        <ul>
                                            @foreach($cauHoi->dapAn as $dapAn)
                                                <li class="{{ $dapAn->dung_sai ? 'correct-answer' : '' }}">
                                                    {{ $dapAn->noi_dung }}
                                                    @if($dapAn->dung_sai)
                                                        <span class="correct-badge">Đúng</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <p>Không có đáp án.</p>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ol>
            @else
                <p>Không có câu hỏi nào trong bài thi này.</p>
            @endif
        </div>
    @else
        <div class="exam-instructions">
            <h2><i class="fas fa-info-circle"></i> Hướng dẫn làm bài</h2>
            <div class="instruction-card">
                <ul>
                    <li><i class="fas fa-question-circle"></i> Bài thi gồm <strong>{{ $exam->tong_so_cau }} câu hỏi</strong>, thời gian làm bài là <strong>{{ $exam->thoi_gian }} phút</strong>.</li>
                    <li><i class="fas fa-hourglass-start"></i> Sau khi bắt đầu làm bài, hệ thống sẽ tính thời gian làm bài.</li>
                    <li><i class="fas fa-hourglass-end"></i> Khi hết thời gian, bài thi sẽ tự động nộp.</li>
                    <li><i class="fas fa-exclamation-circle"></i> Bạn chỉ được làm bài thi một lần duy nhất.</li>
                    @php
                        $hasResult = \App\Models\KetQuaBaiThi::where('ma_bai_thi', $exam->ma_bai_thi)
                            ->where('ma_nguoi_dung', Auth::user()->ma_nguoi_dung)
                            ->exists();
                    @endphp
                    @if($hasResult)
                        <li class="warning-item"><i class="fas fa-ban"></i> <span>Bạn đã làm bài thi này. Không thể làm lại.</span></li>
                    @else
                        <li><i class="fas fa-mouse-pointer"></i> Nhấn nút "Làm bài" để bắt đầu.</li>
                    @endif
                </ul>
            </div>
            <div class="start-exam">
                @if(!$hasResult)
                    <a href="{{ route('exams.take', $exam->slug) }}" class="btn-success"><i class="fas fa-pencil-alt"></i> Làm bài</a>
                @else
                    <a href="{{ route('results.index') }}" class="btn-primary"><i class="fas fa-chart-bar"></i> Xem kết quả</a>
                @endif
            </div>
        </div>
    @endif
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Thêm CSS cho phần hướng dẫn làm bài */
    .exam-instructions h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #3730a3;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .exam-instructions h2 i {
        color: #6366f1;
    }
    .instruction-card {
        background: linear-gradient(120deg, #fff 60%, #f0f4ff 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        border-left: 5px solid #6366f1;
    }
    .instruction-card ul {
        list-style-type: none;
        padding: 0;
    }
    .instruction-card ul li {
        padding: 0.8rem 0;
        display: flex;
        align-items: center;
        gap: 1rem;
        border-bottom: 1px dashed #e2e8f0;
    }
    .instruction-card ul li:last-child {
        border-bottom: none;
    }
    .instruction-card ul li i {
        color: #6366f1;
        font-size: 1.2rem;
        min-width: 24px;
    }
    .instruction-card ul li strong {
        color: #3730a3;
        font-weight: 600;
    }
    .warning-item {
        color: #ef4444;
        font-weight: 600;
    }
    .warning-item i {
        color: #ef4444 !important;
    }
    .start-exam {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
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
    .btn-success {
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(16,185,129,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-success:hover {
        background: linear-gradient(90deg, #34d399 0%, #10b981 100%);
        box-shadow: 0 4px 16px rgba(16,185,129,0.15);
        transform: translateY(-2px) scale(1.03);
    }
    .exam-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .exam-header h1 {
        font-size: 2.2rem;
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
    .exam-info {
        background: linear-gradient(120deg, #fff 60%, #e0e7ff 100%);
        border-radius: 12px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        border-left: 5px solid #6366f1;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
        min-width: 120px;
    }
    .info-item strong i {
        color: #6366f1;
    }
    .exam-actions .btn-primary i,
    .exam-actions .btn-danger i {
        margin-right: 0.5rem;
    }
    .btn-danger {
        background-color: #e3342f;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
    }
    .btn-danger:hover {
        background-color: #cc1f1a;
        text-decoration: none;
    }
    .exam-questions h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #3730a3;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.8rem;
        display: inline-block;
    }
    .exam-questions h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 3px;
    }
    .questions-list {
        counter-reset: question-counter;
        list-style-type: none;
        padding: 0;
    }
    .questions-list li {
        background: linear-gradient(120deg, #fff 60%, #f0f4ff 100%);
        border-left: 4px solid #6366f1;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        padding: 1.5rem 1.8rem;
        border-radius: 12px;
        position: relative;
        transition: all 0.3s ease;
    }
    .questions-list li:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(99,102,241,0.18);
    }
    .questions-list li:before {
        counter-increment: question-counter;
        content: counter(question-counter);
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
    .question-content h3 {
        font-size: 1.2rem;
        font-weight: 600;
        color: #3730a3;
        margin-bottom: 1rem;
    }
    .answers {
        margin-top: 1rem;
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
    }
    .answers ul {
        list-style-type: none;
        padding: 0;
        margin: 0.5rem 0 0 0;
    }
    .answers ul li {
        padding: 0.5rem 1rem;
        margin: 0.5rem 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        box-shadow: none;
        border-left: none;
    }
    .answers ul li:hover {
        transform: none;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
    }
    .answers ul li:before {
        display: none;
    }
    .correct-answer {
        border-left: 3px solid #10b981 !important;
    }
    .correct-badge {
        background: #10b981;
        color: #fff;
        border-radius: 4px;
        padding: 0.2rem 0.6rem;
        font-size: 0.85rem;
        margin-left: 0.5rem;
        font-weight: 600;
    }
    
</style>
@endsection