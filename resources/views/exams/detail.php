<!-- resources/views/exams/detail.blade.php -->
@extends('layouts.main')

@section('title', 'Chi tiết bài thi')

@section('content')
<h1>{{ $exam->ten_bai_thi }}</h1>
<p>Thời gian làm bài: {{ $exam->thoi_gian }} phút</p>
<p>Mô tả: {{ $exam->mo_ta }}</p>

<form action="{{ route('exam.submit', ['id' => $exam->ma_bai_thi]) }}" method="POST">
    @csrf
    <ul class="list-questions">
        @foreach($exam->questions as $question)
            <li>
                <p>{{ $question->noidung }}</p>
                @foreach($question->options as $option)
                    <label>
                        <input type="radio" name="question[{{ $question->id }}]" value="{{ $option->id }}" required />
                        {{ $option->noidung }}
                    </label>
                @endforeach
            </li>
        @endforeach
    </ul>
    <button type="submit" class="btn-primary">Nộp bài</button>
</form>
@endsection