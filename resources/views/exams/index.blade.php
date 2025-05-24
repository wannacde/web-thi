@extends('layout.main')

@section('title', 'Danh sách bài thi')

@section('content')
    <div class="exam-header">
        <h1>Danh sách bài thi</h1>
        
        @if(Auth::user() && Auth::user()->vai_tro != 'hoc_sinh')
            <a href="{{ route('exams.create') }}" class="btn-primary"><i class="fas fa-plus-circle"></i> Tạo bài thi mới</a>
        @endif
    </div>

    @if(isset($exams) && count($exams) > 0)
        <div class="exam-stats">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-book-open"></i></div>
                <div class="stat-content">
                    <div class="stat-value">{{ count($exams) }}</div>
                    <div class="stat-label">Tổng số bài thi</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
                <div class="stat-content">
                    <div class="stat-value">{{ count(array_unique(array_column($exams->toArray(), 'ma_mon_hoc'))) }}</div>
                    <div class="stat-label">Môn học</div>
                </div>
            </div>
        </div>
        
        <div class="exam-filters">
            <input type="text" id="searchExam" placeholder="Tìm kiếm bài thi..." class="search-input">
            <select id="subjectFilter" class="filter-select">
                <option value="">Tất cả môn học</option>
                @php
                    $subjects = [];
                @endphp
                @foreach($exams as $exam)
                    @if(!in_array($exam->monHoc->ma_mon_hoc, $subjects))
                        <option value="{{ $exam->monHoc->ma_mon_hoc }}">{{ $exam->monHoc->ten_mon_hoc }}</option>
                        @php
                            $subjects[] = $exam->monHoc->ma_mon_hoc;
                        @endphp
                    @endif
                @endforeach
            </select>
        </div>

        <ul class="list-exams">
            @foreach($exams as $exam)
                <li class="exam-item" data-subject="{{ $exam->monHoc->ma_mon_hoc }}">
                    <div class="exam-content">
                        <h2>{{ $exam->ten_bai_thi }}</h2>
                        <div class="exam-details">
                            <p><strong><i class="fas fa-book"></i> Môn học:</strong> <span>{{ $exam->monHoc->ten_mon_hoc }}</span></p>
                            <p><strong><i class="fas fa-question-circle"></i> Số câu hỏi:</strong> <span>{{ $exam->tong_so_cau }} câu</span></p>
                            <p><strong><i class="fas fa-clock"></i> Thời gian:</strong> <span>{{ $exam->thoi_gian }} phút</span></p>
                            <p><strong><i class="fas fa-calendar-alt"></i> Ngày tạo:</strong> <span>{{ \Carbon\Carbon::parse($exam->ngay_tao)->format('d/m/Y') }}</span></p>
                        </div>
                    </div>
                    <div class="exam-actions">
                        <a href="{{ route('exams.show', $exam->slug) }}" class="btn-primary"><i class="fas fa-eye"></i> Chi tiết</a>
                        
                        @if(Auth::user()->vai_tro == 'hoc_sinh')
                            <a href="{{ route('exams.take', $exam->slug) }}" class="btn-success"><i class="fas fa-pencil-alt"></i> Làm bài</a>
                        @endif
                        
                        @if(Auth::user()->vai_tro != 'hoc_sinh' && 
                            (Auth::user()->vai_tro == 'quan_tri' || Auth::user()->ma_nguoi_dung == $exam->nguoi_tao))
                            <a href="{{ route('exams.edit', $exam->slug) }}" class="btn-warning"><i class="fas fa-edit"></i> Sửa</a>
                            
                            <form action="{{ route('exams.destroy', $exam->slug) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa bài thi này?')"><i class="fas fa-trash"></i> Xóa</button>
                            </form>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p>Không có bài thi nào.</p>
    @endif
@endsection

@section('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
    }
    .exam-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem 2rem 0 2rem;
    }
    .exam-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #3b3b5c;
        letter-spacing: 1px;
    }
    .exam-header h1:before {
        content: '\f5cb';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.8rem;
        color: #6366f1;
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
    .btn-warning {
        background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(245,158,11,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-warning:hover {
        background: linear-gradient(90deg, #fbbf24 0%, #f59e0b 100%);
        box-shadow: 0 4px 16px rgba(245,158,11,0.15);
        transform: translateY(-2px) scale(1.03);
    }
    
    .btn-danger {
        background: linear-gradient(90deg, #ef4444 0%, #f87171 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(239,68,68,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-danger:hover {
        background: linear-gradient(90deg, #f87171 0%, #ef4444 100%);
        box-shadow: 0 4px 16px rgba(239,68,68,0.15);
        transform: translateY(-2px) scale(1.03);
    }


    .exam-filters {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 0 2rem;
        position: relative;
    }
    .search-input {
        padding: 0.8rem 1rem 0.8rem 2.8rem;
        border: 1px solid #c7d2fe;
        border-radius: 12px;
        font-size: 1rem;
        background: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        width: 100%;
        max-width: 400px;
    }
    .search-input:focus {
        border: 1.5px solid #6366f1;
        outline: none;
        box-shadow: 0 4px 12px rgba(99,102,241,0.12);
    }
    .exam-filters:before {
        content: '\f002';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 3rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6366f1;
        font-size: 1rem;
    }
    .filter-select {
        padding: 0.8rem 1rem;
        border: 1px solid #c7d2fe;
        border-radius: 12px;
        font-size: 1rem;
        background: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        min-width: 180px;
        cursor: pointer;
        background-image: linear-gradient(45deg, transparent 50%, #6366f1 50%), 
                          linear-gradient(135deg, #6366f1 50%, transparent 50%);
        background-position: calc(100% - 20px) calc(1em + 2px), 
                             calc(100% - 15px) calc(1em + 2px);
        background-size: 5px 5px, 5px 5px;
        background-repeat: no-repeat;
        appearance: none;
    }
    .filter-select:focus {
        border: 1.5px solid #6366f1;
        outline: none;
        box-shadow: 0 4px 12px rgba(99,102,241,0.12);
    }
    .list-exams {
        list-style-type: none;
        padding: 0 2rem 2rem 2rem;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 2rem;
    }
    .exam-item {
        background: linear-gradient(120deg, #fff 60%, #e0e7ff 100%);
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(99,102,241,0.10);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 220px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border-left: 5px solid #6366f1;
    }
    .exam-item:hover {
        box-shadow: 0 8px 32px rgba(99,102,241,0.18);
        transform: translateY(-4px) scale(1.02);
    }
    .exam-item:before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, transparent 50%, rgba(99,102,241,0.1) 50%);
        border-radius: 0 0 0 100px;
    }
    .exam-content h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #3730a3;
        margin-bottom: 0.8rem;
        letter-spacing: 0.5px;
        position: relative;
        padding-bottom: 0.8rem;
    }
    .exam-content h2:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 3px;
    }
    .exam-details {
        margin-top: 0.8rem;
        color: #4b5563;
        font-size: 1rem;
    }
    .exam-details p {
        margin: 0.5rem 0;
        display: flex;
        align-items: center;
    }
    .exam-details p strong {
        color: #6366f1;
        margin-right: 0.5rem;
        min-width: 110px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .exam-details p span {
        font-weight: 500;
    }
    .exam-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1.2rem;
    }
    .exam-actions .btn-primary {
        font-size: 0.98rem;
        padding: 0.6rem 1.1rem;
    }
    .exam-stats {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        padding: 0 2rem;
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        padding: 1.2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
        max-width: 250px;
        transition: all 0.3s ease;
        border-bottom: 3px solid #6366f1;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(99,102,241,0.18);
    }
    .stat-icon {
        background: linear-gradient(135deg, #6366f1, #60a5fa);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-content {
        display: flex;
        flex-direction: column;
    }
    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #3730a3;
    }
    .stat-label {
        font-size: 0.9rem;
        color: #6b7280;
    }
    @media (max-width: 768px) {
        .exam-header, .exam-filters, .list-exams, .exam-stats {
            padding: 0 0.5rem;
        }
        .list-exams {
            grid-template-columns: 1fr;
        }
        .exam-stats {
            flex-direction: column;
        }
        .stat-card {
            max-width: 100%;
        }
        .exam-filters {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchExam');
        const subjectFilter = document.getElementById('subjectFilter');
        const examItems = document.querySelectorAll('.exam-item');
        
        // Hiệu ứng hiển thị dần các bài thi
        examItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            setTimeout(() => {
                item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100 + (index * 50));
        });
        
        function filterExams() {
            const searchTerm = searchInput.value.toLowerCase();
            const subjectId = subjectFilter.value;
            let visibleCount = 0;
            
            examItems.forEach(item => {
                const title = item.querySelector('h2').textContent.toLowerCase();
                const itemSubjectId = item.dataset.subject;
                
                const matchesSearch = title.includes(searchTerm);
                const matchesSubject = subjectId === '' || itemSubjectId === subjectId;
                
                if (matchesSearch && matchesSubject) {
                    item.style.display = '';
                    visibleCount++;
                    
                    // Hiệu ứng khi hiển thị lại sau khi lọc
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Cập nhật thống kê
            document.querySelector('.stat-value').textContent = visibleCount;
        }
        
        searchInput.addEventListener('input', filterExams);
        subjectFilter.addEventListener('change', filterExams);
        
        // Hiệu ứng focus cho ô tìm kiếm
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('search-focused');
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('search-focused');
        });
    });
</script>
@endsection