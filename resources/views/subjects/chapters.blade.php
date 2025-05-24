@extends('layout.main')

@section('title', 'Quản lý chương - ' . $subject->ten_mon_hoc)

@section('content')
    <div class="chapters-header">
        <h1>Quản lý chương - {{ $subject->ten_mon_hoc }}</h1>
        <a href="{{ route('subjects.index') }}" class="btn-secondary">Quay lại</a>
    </div>

    <div class="add-chapter-section">
        <h2>Thêm chương mới</h2>
        
        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('subjects.chapters.store', $subject->slug) }}">
            @csrf
            
            <div class="form-group">
                <label for="ten_chuong">Tên chương</label>
                <input type="text" id="ten_chuong" name="ten_chuong" value="{{ old('ten_chuong') }}" required>
            </div>

            <div class="form-group">
                <label for="muc_do">Mức độ</label>
                <select id="muc_do" name="muc_do" required>
                    <option value="de" {{ old('muc_do') == 'de' ? 'selected' : '' }}>Dễ</option>
                    <option value="trung_binh" {{ old('muc_do') == 'trung_binh' ? 'selected' : '' }}>Trung bình</option>
                    <option value="kho" {{ old('muc_do') == 'kho' ? 'selected' : '' }}>Khó</option>
                </select>
            </div>

            <div class="form-group">
                <label for="so_thu_tu">Số thứ tự</label>
                <input type="number" id="so_thu_tu" name="so_thu_tu" value="{{ old('so_thu_tu', count($subject->chuong) + 1) }}" min="1" required>
            </div>

            <div class="form-group">
                <label for="mo_ta">Mô tả</label>
                <textarea id="mo_ta" name="mo_ta" rows="3">{{ old('mo_ta') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Thêm chương</button>
            </div>
        </form>
    </div>

    <div class="chapters-list-section">
        <h2>Danh sách chương</h2>
        
        @if(count($subject->chuong) > 0)
            <table class="chapters-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên chương</th>
                        <th>Mức độ</th>
                        <th>Số câu hỏi</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subject->chuong->sortBy('so_thu_tu') as $chapter)
                        <tr>
                            <td>{{ $chapter->so_thu_tu }}</td>
                            <td>{{ $chapter->ten_chuong }}</td>
                            <td>
                                @if($chapter->muc_do == 'de')
                                    <span class="badge badge-success">Dễ</span>
                                @elseif($chapter->muc_do == 'trung_binh')
                                    <span class="badge badge-warning">Trung bình</span>
                                @else
                                    <span class="badge badge-danger">Khó</span>
                                @endif
                            </td>
                            <td>{{ $chapter->cauHoi->count() }}</td>
                            <td>
                                <button type="button" class="btn-primary edit-chapter" data-id="{{ $chapter->ma_chuong }}" data-name="{{ $chapter->ten_chuong }}" data-level="{{ $chapter->muc_do }}" data-order="{{ $chapter->so_thu_tu }}" data-desc="{{ $chapter->mo_ta }}">Sửa</button>
                                
                                <form action="{{ route('subjects.chapters.destroy', [$subject->slug, $chapter->ma_chuong]) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-primary" onclick="return confirm('Bạn có chắc chắn muốn xóa chương này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Không có chương nào.</p>
        @endif
    </div>

    <!-- Modal chỉnh sửa chương -->
    <div id="editChapterModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Chỉnh sửa chương</h2>
            
            <form id="editChapterForm" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="edit_ten_chuong">Tên chương</label>
                    <input type="text" id="edit_ten_chuong" name="ten_chuong" required>
                </div>

                <div class="form-group">
                    <label for="edit_muc_do">Mức độ</label>
                    <select id="edit_muc_do" name="muc_do" required>
                        <option value="de">Dễ</option>
                        <option value="trung_binh">Trung bình</option>
                        <option value="kho">Khó</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit_so_thu_tu">Số thứ tự</label>
                    <input type="number" id="edit_so_thu_tu" name="so_thu_tu" min="1" required>
                </div>

                <div class="form-group">
                    <label for="edit_mo_ta">Mô tả</label>
                    <textarea id="edit_mo_ta" name="mo_ta" rows="3"></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Cập nhật</button>
                    <button type="button" class="btn-secondary close-modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('styles')
<style>
    .chapters-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .add-chapter-section {
        background-color: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }
    .chapters-table {
        width: 100%;
        border-collapse: collapse;
    }
    .chapters-table th, .chapters-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .chapters-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: bold;
        color: white;
    }
    .badge-success {
        background-color: #28a745;
    }
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }
    .badge-danger {
        background-color: #dc3545;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 2rem;
        border-radius: 8px;
        width: 60%;
        max-width: 600px;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .close:hover {
        color: black;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editChapterModal');
        const editButtons = document.querySelectorAll('.edit-chapter');
        const closeButtons = document.querySelectorAll('.close, .close-modal');
        const editForm = document.getElementById('editChapterForm');
        
        // Mở modal khi nhấn nút sửa
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const chapterId = this.dataset.id;
                const chapterName = this.dataset.name;
                const chapterLevel = this.dataset.level;
                const chapterOrder = this.dataset.order;
                const chapterDesc = this.dataset.desc;
                
                // Cập nhật form
                document.getElementById('edit_ten_chuong').value = chapterName;
                document.getElementById('edit_muc_do').value = chapterLevel;
                document.getElementById('edit_so_thu_tu').value = chapterOrder;
                document.getElementById('edit_mo_ta').value = chapterDesc;
                
                // Cập nhật action của form
                editForm.action = `{{ route('subjects.chapters.update', [$subject->slug, '']) }}/${chapterId}`;
                
                // Hiển thị modal
                modal.style.display = 'block';
            });
        });
        
        // Đóng modal
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                modal.style.display = 'none';
            });
        });
        
        // Đóng modal khi click bên ngoài
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>
@endsection
