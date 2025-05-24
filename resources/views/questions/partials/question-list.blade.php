@if(count($questions) > 0)
    <ul class="list-questions">
        @foreach($questions as $question)
            <li class="question-item" 
                data-subject="{{ $question->chuong->monHoc->ma_mon_hoc }}"
                data-chapter="{{ $question->chuong->ma_chuong }}" 
                data-type="{{ $question->loai_cau_hoi }}">
                <div class="question-content">
                    <h3>{{ $question->noi_dung }}</h3>
                    <div class="question-meta">
                        <span>Môn học: {{ $question->chuong->monHoc->ten_mon_hoc }}</span> |
                        <span>Chương: {{ $question->chuong->ten_chuong }}</span> |
                        <span>Loại: {{ $question->loai_cau_hoi == 'trac_nghiem' ? 'Trắc nghiệm' : 'Điền khuyết' }}</span>
                    </div>
                    
                    @if(count($question->dapAn) > 0)
                        <div class="answers">
                            <strong>Đáp án:</strong>
                            <ul>
                                @foreach($question->dapAn as $dapAn)
                                    <li class="{{ $dapAn->dung_sai ? 'correct-answer' : '' }}">
                                        {{ $dapAn->noi_dung }}
                                        @if($dapAn->dung_sai)
                                            <span class="correct-badge">Đúng</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                
                <div class="question-actions">
                    <a href="{{ route('questions.edit', $question->ma_cau_hoi) }}" class="btn-primary">Sửa</a>
                    <form action="{{ route('questions.destroy', $question->ma_cau_hoi) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary" onclick="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?')">Xóa</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
    
   @if(method_exists($questions, 'links'))
    <div class="pagination">
        {{ $questions->appends(request()->query())->links() }}
    </div>
@endif

@else
    <p>Không có câu hỏi nào.</p>
@endif
