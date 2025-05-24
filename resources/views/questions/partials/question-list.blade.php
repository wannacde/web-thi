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
                        <span><i class="fas fa-book"></i> {{ $question->chuong->monHoc->ten_mon_hoc }}</span> |
                        <span><i class="fas fa-layer-group"></i> {{ $question->chuong->ten_chuong }}</span> |
                        <span><i class="fas fa-question-circle"></i> {{ $question->loai_cau_hoi == 'trac_nghiem' ? 'Trắc nghiệm' : 'Điền khuyết' }}</span>
                    </div>
                    
                    @if(count($question->dapAn) > 0)
                        <div class="answers">
                            <strong><i class="fas fa-check-circle"></i> Đáp án:</strong>
                            <ul>
                                @foreach($question->dapAn as $dapAn)
                                    <li class="{{ $dapAn->dung_sai ? 'correct-answer' : '' }}">
                                        {{ $dapAn->noi_dung }}
                                        @if($dapAn->dung_sai)
                                            <span class="correct-badge"><i class="fas fa-check"></i> Đúng</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                
                <div class="question-actions">
                    <a href="{{ route('questions.edit', $question->ma_cau_hoi) }}" class="icon-btn" title="Sửa"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('questions.destroy', $question->ma_cau_hoi) }}" method="POST" class="inline-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?')"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
    
    @if(method_exists($questions, 'links'))
    <div class="pagination" style="position: static; z-index: 1; margin-bottom: 3rem;">
        {{ $questions->appends(request()->query())->links() }}
    </div>
    @endif
@else
    <div class="no-results">
        <i class="fas fa-search fa-3x"></i>
        <p>Không có câu hỏi nào.</p>
    </div>
@endif