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
                            <td>{{ $result->baiThi->ten_bai_thi }}</td>
                            <td>{{ $result->baiThi->monHoc->ten_mon_hoc }}</td>
                            <td>{{ number_format($result->diem, 1) }}</td>
                            <td>{{ \Carbon\Carbon::parse($result->ngay_nop)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('results.show', $result->ma_ket_qua) }}" class="btn-primary">Chi tiết</a>
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
<style>
    .results-header {
        margin-bottom: 1.5rem;
    }
    .results-filters {
        margin-bottom: 1.5rem;
    }
    .search-input {
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 100%;
    }
    .results-table-container {
        overflow-x: auto;
    }
    .results-table {
        width: 100%;
        border-collapse: collapse;
    }
    .results-table th, .results-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .results-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .results-table tr:hover {
        background-color: #f8f9fa;
    }
    .pagination {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
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