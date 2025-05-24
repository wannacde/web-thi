@extends('layout.main')

@section('title', 'Kết quả bài thi')

@section('content')
    <div class="results-header">
        <h1><i class="fas fa-chart-bar"></i> Kết quả bài thi</h1>
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
                                    <a href="{{ route('results.show', $result->ma_ket_qua) }}" class="btn-primary"><i class="fas fa-eye"></i> Chi tiết</a>
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
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
    }
    .results-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1rem 0;
    }
    .results-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #3730a3;
        position: relative;
        padding-bottom: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .results-header h1:before {
        content: '\f559';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: #6366f1;
    }
    .results-header h1:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 4px;
    }
    .results-filters {
        margin-bottom: 1.5rem;
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
    .results-filters:before {
        content: '\f002';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6366f1;
        font-size: 1rem;
    }
    .results-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .results-table {
        width: 100%;
        border-collapse: collapse;
    }
    .results-table th {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: white;
        font-weight: 600;
        text-align: left;
        padding: 1rem;
        font-size: 1rem;
    }
    .results-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }
    .results-table tr:last-child td {
        border-bottom: none;
    }
    .results-table tr:hover {
        background: #f0f4ff;
    }
    .btn-primary {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.9rem;
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
        transform: translateY(-2px);
    }
    .btn-primary i {
        margin-right: 0.5rem;
    }
    .pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 2rem;
    }
    .pagination .page-item {
        list-style: none;
    }
    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        background: white;
        color: #6366f1;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        transition: all 0.3s ease;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: white;
    }
    .pagination .page-link:hover {
        background: #f0f4ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99,102,241,0.12);
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