<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Bài Thi</title>
</head>
<body>
    <h1>Danh Sách Bài Thi</h1>
    <table>
        <thead>
            <tr>
                <th>Tên Bài Thi</th>
                <th>Thời Gian</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($baiThiList as $baiThi)
                <tr>
                    <td>{{ $baiThi->ten_bai_thi }}</td>
                    <td>{{ $baiThi->thoi_gian }} phút</td>
                    <td>
                        <a href="{{ route('exams.show', $baiThi->slug) }}">Xem Chi Tiết</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>