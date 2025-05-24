@extends('layout.main')

@section('title', $subject->ten_mon_hoc)

@section('content')
    <div class="subject-header">
        <div class="subject-title">
            <div class="subject-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <div>
                <h1>{{ $subject->ten_mon_hoc }}</h1>
                <p class="subject-meta">{{ $subject->mo_ta ?? 'Không có mô tả' }}</p>
            </div>
        </div>
        <div class="subject-actions">
            <a href="{{ route('subjects.index') }}" class="btn-action btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('subjects.chapters', $subject->slug) }}" class="btn-action btn-primary">
                <i class="fas fa-layer-group"></i> Quản lý chương
            </a>
            
            @if(Auth::user()->vai_tro == 'quan_tri')
                <a href="{{ route('subjects.edit', $subject->slug) }}" class="btn-action btn-edit">
                    <i class="fas fa-edit"></i> Sửa
                </a>
                
                <form action="{{ route('subjects.destroy', $subject->slug) }}" method="POST" class="inline-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-action btn-delete" onclick="return confirm('Bạn có chắc chắn muốn xóa môn học này?')">
                        <i class="fas fa-trash-alt"></i> Xóa
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="subject-stats">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ count($subject->chuong) }}</div>
                <div class="stat-label">Chương</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-question-circle"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ $subject->chuong->sum(function($chapter) { return $chapter->cauHoi->count(); }) }}</div>
                <div class="stat-label">Câu hỏi</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-content">
                <div class="stat-value">{{ count($subject->baiThi) }}</div>
                <div class="stat-label">Bài thi</div>
            </div>
        </div>
    </div>
    
    <div class="content-tabs">
        <div class="tab-header">
            <button class="tab-btn active" data-tab="chapters">
                <i class="fas fa-layer-group"></i> Danh sách chương
            </button>
            <button class="tab-btn" data-tab="exams">
                <i class="fas fa-file-alt"></i> Bài thi thuộc môn học
            </button>
        </div>
        
        <div class="tab-content active" id="chapters-tab">
            @if(count($subject->chuong) > 0)
                <div class="chapters-grid">
                    @foreach($subject->chuong->sortBy('so_thu_tu') as $chapter)
                        <div class="chapter-card">
                            <div class="chapter-header">
                                <div class="chapter-number">{{ $chapter->so_thu_tu }}</div>
                                <span class="chapter-level 
                                    @if($chapter->muc_do == 'de') level-easy
                                    @elseif($chapter->muc_do == 'trung_binh') level-medium
                                    @else level-hard
                                    @endif">
                                    {{ $chapter->muc_do == 'de' ? 'Dễ' : ($chapter->muc_do == 'trung_binh' ? 'Trung bình' : 'Khó') }}
                                </span>
                            </div>
                            
                            <div class="chapter-body">
                                <h3>{{ $chapter->ten_chuong }}</h3>
                                <p class="chapter-description">{{ $chapter->mo_ta ?? 'Không có mô tả' }}</p>
                            </div>
                            
                            <div class="chapter-footer">
                                <div class="chapter-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-question-circle"></i>
                                        <span>{{ $chapter->cauHoi->count() }} câu hỏi</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-layer-group"></i>
                    <p>Không có chương nào.</p>
                    <a href="{{ route('subjects.chapters', $subject->slug) }}" class="btn-primary">
                        <i class="fas fa-plus"></i> Thêm chương
                    </a>
                </div>
            @endif
        </div>
        
        <div class="tab-content" id="exams-tab">
            @if(count($subject->baiThi) > 0)
                <div class="exams-grid">
                    @foreach($subject->baiThi as $exam)
                        <div class="exam-card">
                            <div class="exam-header">
                                <h3>{{ $exam->ten_bai_thi }}</h3>
                            </div>
                            
                            <div class="exam-body">
                                <div class="exam-stats">
                                    <div class="stat-item">
                                        <i class="fas fa-question-circle"></i>
                                        <span>{{ $exam->tong_so_cau }} câu hỏi</span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="fas fa-clock"></i>
                                        <span>{{ $exam->thoi_gian }} phút</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="exam-footer">
                                <a href="{{ route('exams.show', $exam->slug) }}" class="btn-primary">
                                    <i class="fas fa-eye"></i> Chi tiết
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-file-alt"></i>
                    <p>Không có bài thi nào.</p>
                    <a href="{{ route('exams.create') }}" class="btn-primary">
                        <i class="fas fa-plus"></i> Tạo bài thi
                    </a>
                </div>
            @endif
        </div>
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
    }
    
    /* Subject Header */
    .subject-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .subject-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .subject-icon {
        font-size: 2.5rem;
        color: #3490dc;
        background: rgba(52, 144, 220, 0.1);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .subject-title h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
    }
    .subject-meta {
        color: #6b7280;
        margin: 0;
    }
    .subject-actions {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
    }
    
    /* Action Buttons */
    .btn-action {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    .btn-primary {
        background-color: #3490dc;
        color: white;
    }
    .btn-primary:hover {
        background-color: #2779bd;
        transform: translateY(-2px);
    }
    .btn-secondary {
        background-color: #f1f5f9;
        color: #4b5563;
    }
    .btn-secondary:hover {
        background-color: #e2e8f0;
        transform: translateY(-2px);
    }
    .btn-edit {
        background-color: #f59e0b;
        color: white;
    }
    .btn-edit:hover {
        background-color: #d97706;
        transform: translateY(-2px);
    }
    .btn-delete {
        background-color: #ef4444;
        color: white;
    }
    .btn-delete:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
    }
    .inline-form {
        display: inline;
    }
    
    /* Stats Cards */
    .subject-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .stat-icon {
        font-size: 2rem;
        color: #3490dc;
        background: rgba(52, 144, 220, 0.1);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-content {
        flex-grow: 1;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1;
    }
    .stat-label {
        color: #6b7280;
        font-size: 1rem;
        margin-top: 0.3rem;
    }
    
    /* Tabs */
    .content-tabs {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .tab-header {
        display: flex;
        border-bottom: 1px solid #e2e8f0;
    }
    .tab-btn {
        padding: 1rem 1.5rem;
        font-weight: 600;
        font-size: 1rem;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
        transition: all 0.2s ease;
        border-bottom: 3px solid transparent;
    }
    .tab-btn:hover {
        color: #3490dc;
    }
    .tab-btn.active {
        color: #3490dc;
        border-bottom-color: #3490dc;
    }
    .tab-content {
        display: none;
        padding: 2rem;
    }
    .tab-content.active {
        display: block;
    }
    
    /* Chapters Grid */
    .chapters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .chapter-card {
        background: #f8fafc;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .chapter-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .chapter-header {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
    }
    .chapter-number {
        background: #3490dc;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }
    .chapter-level {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .level-easy {
        background-color: #10b981;
        color: white;
    }
    .level-medium {
        background-color: #f59e0b;
        color: white;
    }
    .level-hard {
        background-color: #ef4444;
        color: white;
    }
    .chapter-body {
        padding: 1.5rem;
    }
    .chapter-body h3 {
        margin-top: 0;
        margin-bottom: 0.8rem;
        font-size: 1.2rem;
        color: #2d3748;
    }
    .chapter-description {
        color: #6b7280;
        margin: 0;
        line-height: 1.5;
    }
    .chapter-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        background-color: white;
    }
    .chapter-stats {
        display: flex;
        gap: 1rem;
    }
    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6b7280;
    }
    .stat-item i {
        color: #3490dc;
    }
    
    /* Exams Grid */
    .exams-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .exam-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .exam-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .exam-header {
        background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
        color: white;
        padding: 1.2rem;
    }
    .exam-header h3 {
        margin: 0;
        font-size: 1.2rem;
    }
    .exam-body {
        padding: 1.5rem;
    }
    .exam-stats {
        display: flex;
        justify-content: space-between;
    }
    .exam-footer {
        padding: 1.2rem;
        border-top: 1px solid #e2e8f0;
        text-align: center;
    }
    .exam-footer .btn-primary {
        width: 100%;
        justify-content: center;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 0;
        color: #6b7280;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .empty-state i {
        font-size: 3rem;
        color: #3490dc;
        margin-bottom: 1rem;
    }
    .empty-state p {
        font-size: 1.2rem;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .subject-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .subject-actions {
            width: 100%;
            justify-content: flex-start;
        }
        .tab-header {
            flex-direction: column;
        }
        .tab-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons and contents
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show corresponding content
            const tabId = this.getAttribute('data-tab');
            document.getElementById(`${tabId}-tab`).classList.add('active');
        });
    });
});
</script>
@endsection