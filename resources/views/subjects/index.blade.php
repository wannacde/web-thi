@extends('layout.main')

@section('title', 'Danh sách môn học')

@section('content')
    <div class="subjects-header">
        <h1><i class="fas fa-book"></i> Danh sách môn học</h1>
        @if(Auth::user()->vai_tro == 'quan_tri')
            <a href="{{ route('subjects.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm môn học</a>
        @endif
    </div>

    @if(count($subjects) > 0)
        <div class="subjects-grid">
            @foreach($subjects as $subject)
                <div class="subject-card">
                    <div class="subject-card-header">
                        <div class="subject-icon">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <h2>{{ $subject->ten_mon_hoc }}</h2>
                    </div>
                    
                    <div class="subject-card-body">
                        <p class="subject-description">{{ $subject->mo_ta ?? 'Không có mô tả' }}</p>
                        <div class="subject-stats">
                            <div class="stat-item">
                                <i class="fas fa-layer-group"></i>
                                <span>{{ $subject->chuong_count }} chương</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="subject-card-footer">
                        <a href="{{ route('subjects.show', $subject->slug) }}" class="btn-action btn-view" title="Chi tiết">
                            <i class="fas fa-eye"></i> Chi tiết
                        </a>
                        <a href="{{ route('subjects.chapters', $subject->slug) }}" class="btn-action btn-manage" title="Quản lý chương">
                            <i class="fas fa-layer-group"></i> Chương
                        </a>
                        @if(Auth::user()->vai_tro == 'quan_tri')
                            <div class="admin-actions">
                                <a href="{{ route('subjects.edit', $subject->slug) }}" class="icon-btn" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('subjects.destroy', $subject->slug) }}" method="POST" class="inline-form" onsubmit="return confirm('Bạn có chắc chắn muốn xóa môn học này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-subjects">
            <i class="fas fa-book-open"></i>
            <p>Không có môn học nào.</p>
            @if(Auth::user()->vai_tro == 'quan_tri')
                <a href="{{ route('subjects.create') }}" class="btn-primary"><i class="fas fa-plus"></i> Thêm môn học đầu tiên</a>
            @endif
        </div>
    @endif
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
    .subjects-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .subjects-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    
    /* Grid Layout */
    .subjects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
    }
    
    /* Subject Card */
    .subject-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .subject-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    /* Card Header */
    .subject-card-header {
        background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
        color: white;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .subject-card-header h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        color: white;
    }
    .subject-icon {
        font-size: 2rem;
        color: white;
        background: rgba(255,255,255,0.2);
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Card Body */
    .subject-card-body {
        padding: 1.5rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .subject-description {
        color: #4a5568;
        margin-top: 0;
        margin-bottom: 1rem;
        flex-grow: 1;
        line-height: 1.5;
    }
    .subject-stats {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: auto;
    }
    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #4a5568;
        font-weight: 500;
    }
    .stat-item i {
        color: #3490dc;
    }
    
    /* Card Footer */
    .subject-card-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        background-color: #f8fafc;
    }
    .btn-action {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        transition: all 0.2s ease;
        flex: 1;
        justify-content: center;
    }
    .btn-view {
        background-color: #3490dc;
        color: white;
    }
    .btn-view:hover {
        background-color: #2779bd;
        transform: translateY(-2px);
    }
    .btn-manage {
        background-color: #4fd1c5;
        color: white;
    }
    .btn-manage:hover {
        background-color: #38b2ac;
        transform: translateY(-2px);
    }
    .admin-actions {
        display: flex;
        gap: 0.5rem;
        margin-left: auto;
    }
    .icon-btn {
        background: #f1f5fa;
        border: none;
        color: #3490dc;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        cursor: pointer;
        text-decoration: none;
    }
    .icon-btn:hover {
        background: #3490dc;
        color: #fff;
        transform: translateY(-2px);
    }
    
    /* Empty State */
    .no-subjects {
        text-align: center;
        padding: 3rem 0;
        color: #6b7280;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .no-subjects i {
        font-size: 3rem;
        color: #3490dc;
        margin-bottom: 1rem;
    }
    .no-subjects p {
        font-size: 1.2rem;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .subjects-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .subject-card-footer {
            flex-direction: column;
            gap: 0.8rem;
        }
        .btn-action {
            width: 100%;
        }
        .admin-actions {
            margin-left: 0;
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection