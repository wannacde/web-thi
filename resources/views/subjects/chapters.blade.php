@extends('layout.main')

@section('title', 'Quản lý chương - ' . $subject->ten_mon_hoc)

@section('content')
    <div class="page-header">
        <div class="page-title">
            <div class="subject-icon">
                <i class="fas fa-book-reader"></i>
            </div>
            <div>
                <h1>Quản lý chương</h1>
                <p class="subject-meta">{{ $subject->ten_mon_hoc }}</p>
            </div>
        </div>
        <div class="page-actions">
            <a href="{{ route('subjects.show', $subject->slug) }}" class="btn-secondary">
                <i class="fas fa-eye"></i> Xem chi tiết môn học
            </a>
            <a href="{{ route('subjects.index') }}" class="btn-light">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="content-tabs">
        <div class="tab-header">
            <button class="tab-btn active" data-tab="list">
                <i class="fas fa-list"></i> Danh sách chương
            </button>
            <button class="tab-btn" data-tab="add">
                <i class="fas fa-plus"></i> Thêm chương mới
            </button>
        </div>
        
        <div class="tab-content active" id="list-tab">
            @if(count($subject->chuong) > 0)
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th width="60">STT</th>
                                <th>Tên chương</th>
                                <th width="120">Mức độ</th>
                                <th width="120">Số câu hỏi</th>
                                <th width="180">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subject->chuong->sortBy('so_thu_tu') as $chapter)
                                <tr>
                                    <td class="text-center">{{ $chapter->so_thu_tu }}</td>
                                    <td>
                                        <div class="chapter-name">{{ $chapter->ten_chuong }}</div>
                                        @if($chapter->mo_ta)
                                            <div class="chapter-desc">{{ $chapter->mo_ta }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($chapter->muc_do == 'de')
                                            <span class="badge badge-success">Dễ</span>
                                        @elseif($chapter->muc_do == 'trung_binh')
                                            <span class="badge badge-warning">Trung bình</span>
                                        @else
                                            <span class="badge badge-danger">Khó</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="question-count">{{ $chapter->cauHoi->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn-icon btn-edit edit-chapter" 
                                                data-id="{{ $chapter->ma_chuong }}" 
                                                data-name="{{ $chapter->ten_chuong }}" 
                                                data-level="{{ $chapter->muc_do }}" 
                                                data-order="{{ $chapter->so_thu_tu }}" 
                                                data-desc="{{ $chapter->mo_ta }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                            <form action="{{ route('subjects.chapters.destroy', [$subject->slug, $chapter->ma_chuong]) }}" method="POST" class="inline-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-delete" 
                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa chương này? Tất cả câu hỏi thuộc chương này sẽ bị xóa.')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-layer-group"></i>
                    <p>Chưa có chương nào trong môn học này.</p>
                    <button class="btn-primary add-chapter-btn">
                        <i class="fas fa-plus"></i> Thêm chương đầu tiên
                    </button>
                </div>
            @endif
        </div>
        
        <div class="tab-content" id="add-tab">
            <div class="form-card">
                <div class="form-card-header">
                    <h2><i class="fas fa-plus-circle"></i> Thêm chương mới</h2>
                </div>
                
                <div class="form-card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('subjects.chapters.store', $subject->slug) }}">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="ten_chuong">Tên chương <span class="required">*</span></label>
                                <input type="text" id="ten_chuong" name="ten_chuong" value="{{ old('ten_chuong') }}" 
                                    class="form-control" required placeholder="Nhập tên chương">
                            </div>
                            
                            <div class="form-group col-md-2">
                                <label for="so_thu_tu">Số thứ tự <span class="required">*</span></label>
                                <input type="number" id="so_thu_tu" name="so_thu_tu" 
                                    value="{{ old('so_thu_tu', count($subject->chuong) + 1) }}" 
                                    min="1" class="form-control" required>
                            </div>
                            
                            <div class="form-group col-md-2">
                                <label for="muc_do">Mức độ <span class="required">*</span></label>
                                <select id="muc_do" name="muc_do" class="form-control" required>
                                    <option value="de" {{ old('muc_do') == 'de' ? 'selected' : '' }}>Dễ</option>
                                    <option value="trung_binh" {{ old('muc_do') == 'trung_binh' ? 'selected' : '' }}>Trung bình</option>
                                    <option value="kho" {{ old('muc_do') == 'kho' ? 'selected' : '' }}>Khó</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mo_ta">Mô tả</label>
                            <textarea id="mo_ta" name="mo_ta" rows="3" class="form-control"
                                placeholder="Nhập mô tả về nội dung của chương (không bắt buộc)">{{ old('mo_ta') }}</textarea>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Lưu chương
                            </button>
                            <button type="reset" class="btn-light">
                                <i class="fas fa-redo"></i> Làm lại
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chỉnh sửa chương -->
    <div id="editChapterModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-edit"></i> Chỉnh sửa chương</h2>
                <span class="close">&times;</span>
            </div>
            
            <div class="modal-body">
                <form id="editChapterForm" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="edit_ten_chuong">Tên chương <span class="required">*</span></label>
                            <input type="text" id="edit_ten_chuong" name="ten_chuong" class="form-control" required>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <label for="edit_so_thu_tu">Số thứ tự <span class="required">*</span></label>
                            <input type="number" id="edit_so_thu_tu" name="so_thu_tu" min="1" class="form-control" required>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <label for="edit_muc_do">Mức độ <span class="required">*</span></label>
                            <select id="edit_muc_do" name="muc_do" class="form-control" required>
                                <option value="de">Dễ</option>
                                <option value="trung_binh">Trung bình</option>
                                <option value="kho">Khó</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_mo_ta">Mô tả</label>
                        <textarea id="edit_mo_ta" name="mo_ta" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Cập nhật
                        </button>
                        <button type="button" class="btn-light close-modal">
                            <i class="fas fa-times"></i> Hủy
                        </button>
                    </div>
                </form>
            </div>
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
    
    /* Page Header */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .page-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .subject-icon {
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
    .page-title h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
    }
    .subject-meta {
        color: #6b7280;
        margin: 0;
        font-size: 1.1rem;
    }
    .page-actions {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
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
    
    /* Table */
    .table-responsive {
        overflow-x: auto;
    }
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    .data-table th, .data-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #4b5563;
    }
    .data-table tr:hover {
        background-color: #f9fafb;
    }
    .text-center {
        text-align: center;
    }
    .chapter-name {
        font-weight: 600;
        color: #2d3748;
    }
    .chapter-desc {
        font-size: 0.875rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    .question-count {
        font-weight: 600;
        color: #3490dc;
    }
    
    /* Badges */
    .badge {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-align: center;
    }
    .badge-success {
        background-color: #10b981;
        color: white;
    }
    .badge-warning {
        background-color: #f59e0b;
        color: white;
    }
    .badge-danger {
        background-color: #ef4444;
        color: white;
    }
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
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
    
    /* Form */
    .form-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }
    .form-card-header {
        background: linear-gradient(90deg, #3490dc 0%, #6a82fb 100%);
        color: white;
        padding: 1.2rem 1.5rem;
    }
    .form-card-header h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .form-card-body {
        padding: 2rem 1.5rem;
    }
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -0.75rem;
        margin-left: -0.75rem;
    }
    .col-md-8 {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }
    .col-md-2 {
        flex: 0 0 16.666667%;
        max-width: 16.666667%;
        padding-right: 0.75rem;
        padding-left: 0.75rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #4b5563;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    .form-control:focus {
        border-color: #3490dc;
        outline: 0;
        box-shadow: 0 0 0 3px rgba(52, 144, 220, 0.25);
    }
    .required {
        color: #ef4444;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    /* Buttons */
    .btn-primary, .btn-secondary, .btn-light {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
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
        box-shadow: 0 4px 10px rgba(52, 144, 220, 0.2);
    }
    .btn-secondary {
        background-color: #6b7280;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(107, 114, 128, 0.2);
    }
    .btn-light {
        background-color: #6b7280;
        color:rgb(252, 252, 252);
    }
    .btn-light:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
    }
     
    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        overflow: auto;
    }
    .modal-content {
        background-color: white;
        margin: 10% auto;
        border-radius: 12px;
        width: 80%;
        max-width: 800px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        animation: modalFadeIn 0.3s;
    }
    .modal-header {
        background: linear-gradient(90deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
        padding: 1.2rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    .modal-body {
        padding: 2rem 1.5rem;
    }
    .close {
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover {
        color: #f3f4f6;
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
    
    /* Alert */
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    .alert-danger {
        background-color: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }
    .alert i {
        font-size: 1.2rem;
        margin-top: 0.2rem;
    }
    .alert ul {
        margin: 0;
        padding-left: 1rem;
    }
    
    /* Animations */
    @keyframes modalFadeIn {
        from {opacity: 0; transform: translateY(-20px);}
        to {opacity: 1; transform: translateY(0);}
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .page-actions {
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
        .form-row {
            flex-direction: column;
        }
        .col-md-8, .col-md-2 {
            max-width: 100%;
            flex: 0 0 100%;
        }
        .form-actions {
            flex-direction: column;
        }
        .btn-primary, .btn-light {
            width: 100%;
            justify-content: center;
        }
        .modal-content {
            width: 95%;
            margin: 5% auto;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
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
        
        // Empty state button
        const addChapterBtn = document.querySelector('.add-chapter-btn');
        if (addChapterBtn) {
            addChapterBtn.addEventListener('click', function() {
                // Switch to add tab
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                document.querySelector('[data-tab="add"]').classList.add('active');
                document.getElementById('add-tab').classList.add('active');
            });
        }
        
        // Modal handling
        const modal = document.getElementById('editChapterModal');
        const editButtons = document.querySelectorAll('.edit-chapter');
        const closeButtons = document.querySelectorAll('.close, .close-modal');
        const editForm = document.getElementById('editChapterForm');
        
        // Open modal when edit button is clicked
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const chapterId = this.dataset.id;
                const chapterName = this.dataset.name;
                const chapterLevel = this.dataset.level;
                const chapterOrder = this.dataset.order;
                const chapterDesc = this.dataset.desc;
                
                // Update form fields
                document.getElementById('edit_ten_chuong').value = chapterName;
                document.getElementById('edit_muc_do').value = chapterLevel;
                document.getElementById('edit_so_thu_tu').value = chapterOrder;
                document.getElementById('edit_mo_ta').value = chapterDesc;
                
                // Update form action
                editForm.action = `{{ route('subjects.chapters.update', [$subject->slug, '']) }}/${chapterId}`;
                
                // Show modal
                modal.style.display = 'block';
            });
        });
        
        // Close modal
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });
        
        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>
@endsection