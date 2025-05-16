<!-- resources/views/results/statistics.blade.php -->
@extends('layout.main')

@section('title', 'Thống kê kết quả')

@section('content')
    <h1>Thống kê kết quả bài thi</h1>
    
    @if(count($examStats) > 0)
        <div class="stats-table-container">
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>Bài thi</th>
                        <th>Số lượt làm</th>
                        <th>Điểm trung bình</th>
                        <th>Điểm cao nhất</th>
                        <th>Điểm thấp nhất</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examStats as $stat)
                        <tr>
                            <td>{{ $stat->ten_bai_thi }}</td>
                            <td>{{ $stat->total_attempts }}</td>
                            <td>{{ number_format($stat->average_score, 1) }}</td>
                            <td>{{ number_format($stat->highest_score, 1) }}</td>
                            <td>{{ number_format($stat->lowest_score, 1) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="chart-container">
            <canvas id="examStatsChart"></canvas>
        </div>
    @else
        <p>Không có dữ liệu thống kê.</p>
    @endif
@endsection

@section('styles')
<style>
    .stats-table-container {
        margin-bottom: 2rem;
        overflow-x: auto;
    }
    .stats-table {
        width: 100%;
        border-collapse: collapse;
    }
    .stats-table th, .stats-table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
    .stats-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .chart-container {
        height: 400px;
        margin-top: 2rem;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const examStats = @json($examStats);
        
        if (examStats.length > 0) {
            const ctx = document.getElementById('examStatsChart').getContext('2d');
            
            const labels = examStats.map(stat => stat.ten_bai_thi);
            const avgScores = examStats.map(stat => stat.average_score);
            const attempts = examStats.map(stat => stat.total_attempts);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Điểm trung bình',
                            data: avgScores,
                            backgroundColor: 'rgba(54, 162, 235, 0.5)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Số lượt làm',
                            data: attempts,
                            backgroundColor: 'rgba(255, 99, 132, 0.5)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Điểm'
                            },
                            max: 10
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Số lượt làm'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
