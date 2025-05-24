@extends('layout.main')

@section('title', $exam->ten_bai_thi)

@section('content')
    <div class="exam-header">
        <h1>{{ $exam->ten_bai_thi }}</h1>
        <div class="exam-actions">
            @if(Auth::user()->vai_tro != 'hoc_sinh' && 
                (Auth::user()->vai_tro == 'quan_tri' || Auth::user()->ma_nguoi_dung == $exam->nguoi_tao))
                <a href="{{ route('exams.edit', $exam->slug) }}" class="btn-primary"><i class="fas fa-edit"></i>Sửa</a>
                <form action="{{ route('exams.destroy', $exam->slug) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-primary" onclick="return confirm('Bạn có chắc chắn muốn xóa bài thi này?')"><i class="fas fa-trash"></i>Xóa</button>
                </form>
            @endif
        </div>
    </div>

    <div class="exam-info">
        <div class="info-item">
            <strong>Môn học:</strong> {{ $exam->monHoc->ten_mon_hoc }}
        </div>
        <div class="info-item">
            <strong>Số câu hỏi:</strong> {{ $exam->tong_so_cau }}
        </div>
        <div class="info-item">
            <strong>Thời gian:</strong> {{ $exam->thoi_gian }} phút
        </div>
        <div class="info-item">
            <strong>Người tạo:</strong> 
            @if(isset($exam->nguoiTao))
                {{ $exam->nguoiTao->ho_ten }}
            @else
                Không xác định
            @endif
        </div>
        <div class="info-item">
            <strong>Ngày tạo:</strong> {{ \Carbon\Carbon::parse($exam->ngay_tao)->format('d/m/Y H:i') }}
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
            <h2>Hướng dẫn làm bài</h2>
            <ul>
                <li>Bài thi gồm {{ $exam->tong_so_cau }} câu hỏi, thời gian làm bài là {{ $exam->thoi_gian }} phút.</li>
                <li>Sau khi bắt đầu làm bài, hệ thống sẽ tính thời gian làm bài.</li>
                <li>Khi hết thời gian, bài thi sẽ tự động nộp.</li>
                <li>Bạn chỉ được làm bài thi một lần duy nhất.</li>
                @php
                    $hasResult = \App\Models\KetQuaBaiThi::where('ma_bai_thi', $exam->ma_bai_thi)
                        ->where('ma_nguoi_dung', Auth::user()->ma_nguoi_dung)
                        ->exists();
                @endphp
                @if($hasResult)
                    <li><span style="color: #e3342f; font-weight: bold;">Bạn đã làm bài thi này. Không thể làm lại.</span></li>
                @else
                    <li>Nhấn nút "Làm bài" để bắt đầu.</li>
                @endif
            </ul>
            <div class="start-exam">
                @if(!$hasResult)
                    <a href="{{ route('exams.take', $exam->slug) }}" class="btn-primary">Làm bài</a>
                @else
                    <a href="{{ route('results.index') }}" class="btn-primary">Xem kết quả</a>
                @endif
            </div>
        </div>
    @endif
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
    .exam-info {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(52,144,220,0.08);
    }
    .exam-actions .btn-primary i {
        margin-right: 0.5rem;
    }
    .questions-list li {
        background: #fff;
        border-left: 4px solid #3490dc;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(52,144,220,0.08);
        padding: 1rem 1.5rem;
        border-radius: 8px;
    }
    .correct-badge {
        background: #38c172;
        color: #fff;
        border-radius: 4px;
        padding: 0.1rem 0.5rem;
        font-size: 0.9rem;
        margin-left: 0.5rem;
    }
</style>
@endsection