@extends('layout.main')

@section('title', 'Kết quả bài thi')

@section('content')
    <div class="results-header">
        <h1>Kết quả bài thi</h1>
    </div>

    @if(isset($results) && count($results) > 0)
        <div class="results-filters">
            <input type="text" id="searchResult" placeholder="Tìm kiếm kết quả..." class="search-input">
        </div>

        <div class="results-table-container">
            <table class="results-table">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Bài thi</th>
                        <th>Môn học</th>
                        <th>Điểm</th>
                        <th>Ngày nộp</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $result)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $result->baiThi ? $result->baiThi->ten_bai_thi : '[Bài thi đã bị xóa]' }}</td>
                            <td>{{ $result->baiThi && $result->baiThi->monHoc ? $result->baiThi->monHoc->ten_mon_hoc : '' }}</td>
                            <td>{{ number_format($result->diem, 1) }}</td>
                            <td>{{ \Carbon\Carbon::parse($result->ngay_nop)->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($result->baiThi)
                                    <a href="{{ route('results.show', $result->ma_ket_qua) }}" class="btn-primary">Chi tiết</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(method_exists($results, 'links'))
            <div class="pagination">
                {{ $results->links() }}
            </div>
        @endif
    @else
        <p>Không có kết quả bài thi nào.</p>
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
    .results-header h1:before {
        content: '\f559';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        margin-right: 0.5rem;
        color: #3490dc;
    }
    .results-table th, .results-table td {
        vertical-align: middle;
    }
    .btn-primary i {
        margin-right: 0.5rem;
    }
    .results-table tr:hover {
        background: #e0eafc;
    }
    .search-input {
        background: #f8f9fa;
        border: 1px solid #3490dc;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 1rem;
        margin-bottom: 1rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchResult');
        const tableRows = document.querySelectorAll('.results-table tbody tr');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection