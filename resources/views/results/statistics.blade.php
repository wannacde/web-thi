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
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    body {
        font-family: 'Montserrat', Arial, sans-serif;
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
    }
    h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #3730a3;
        position: relative;
        padding-bottom: 0.8rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    h1:before {
        content: '\f201';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: #6366f1;
    }
    h1:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 4px;
    }
    .stats-table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    .stats-table {
        width: 100%;
        border-collapse: collapse;
    }
    .stats-table th {
        background: linear-gradient(90deg, #6366f1 0%, #60a5fa 100%);
        color: white;
        font-weight: 600;
        text-align: left;
        padding: 1rem;
        font-size: 1rem;
    }
    .stats-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }
    .stats-table tr:last-child td {
        border-bottom: none;
    }
    .stats-table tr:hover {
        background: #f0f4ff;
    }
    .chart-container {
        height: 500px;
        margin-top: 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(99,102,241,0.12);
        padding: 2rem;
        position: relative;
    }
    .chart-container:before {
        content: 'Biểu đồ thống kê';
        position: absolute;
        top: 1rem;
        left: 1rem;
        font-size: 1.2rem;
        font-weight: 600;
        color: #3730a3;
    }
    .chart-container:after {
        content: '';
        position: absolute;
        top: 3rem;
        left: 1rem;
        width: 50px;
        height: 3px;
        background: linear-gradient(90deg, #6366f1, #60a5fa);
        border-radius: 3px;
    }
    canvas {
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
                            backgroundColor: 'rgba(99, 102, 241, 0.7)',
                            borderColor: 'rgba(99, 102, 241, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Số lượt làm',
                            data: attempts,
                            backgroundColor: 'rgba(16, 185, 129, 0.7)',
                            borderColor: 'rgba(16, 185, 129, 1)',
                            borderWidth: 1,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: {
                                    family: 'Montserrat',
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            titleColor: '#3730a3',
                            bodyColor: '#4b5563',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            titleFont: {
                                family: 'Montserrat',
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                family: 'Montserrat',
                                size: 12
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Điểm',
                                font: {
                                    family: 'Montserrat',
                                    size: 12,
                                    weight: 'bold'
                                }
                            },
                            max: 10,
                            ticks: {
                                font: {
                                    family: 'Montserrat'
                                }
                            }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Số lượt làm',
                                font: {
                                    family: 'Montserrat',
                                    size: 12,
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                font: {
                                    family: 'Montserrat'
                                }
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    family: 'Montserrat'
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

@endsection
