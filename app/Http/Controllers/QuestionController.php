<?php

namespace App\Http\Controllers;
use App\Models\CauHoi;
use App\Models\Chuong;
use App\Models\DapAn;
use App\Models\MonHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    /**
     * Hiển thị danh sách câu hỏi
     */
    public function index()
{
    if (Auth::user()->vai_tro == 'hoc_sinh') {
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
    }
    
    $subjects = MonHoc::all();
    $chapters = Chuong::with('monHoc')->get();
    
    // Nếu là giáo viên, chỉ hiển thị câu hỏi của họ
    if (Auth::user()->vai_tro == 'giao_vien') {
        $questions = CauHoi::where('nguoi_tao', Auth::id())
            ->with(['chuong.monHoc', 'dapAn'])
            ->paginate(10)
            ->withQueryString();
    } else {
        // Nếu là admin, hiển thị tất cả câu hỏi
        $questions = CauHoi::with(['chuong.monHoc', 'dapAn'])
            ->paginate(10)
            ->withQueryString();
    }
    
    return view('questions.index', compact('questions', 'subjects', 'chapters'));
}

public function search(Request $request)
{
    try {
        $query = CauHoi::query()
            ->with(['chuong.monHoc', 'dapAn'])
            ->orderBy('ma_cau_hoi', 'desc');

        if ($request->filled('search')) {
            $query->where('noi_dung', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('subject')) {
            $query->whereHas('chuong', function($q) use ($request) {
                $q->where('ma_mon_hoc', $request->subject);
            });
        }

        if ($request->filled('chapter')) {
            $query->where('ma_chuong', $request->chapter);
        }

        if ($request->filled('type')) {
            $query->where('loai_cau_hoi', $request->type);
        }

        if (Auth::user()->vai_tro == 'giao_vien') {
            $query->where('nguoi_tao', Auth::id());
        }

        $questions = $query->paginate(10);

        // Nếu là request AJAX, trả về view partial
        if ($request->ajax()) {
            $view = view('questions.partials.question-list', compact('questions'))->render();
            return response()->json([
                'success' => true,
                'html' => $view,
                'pagination' => $questions->links()->toHtml()
            ]);
        }

        $subjects = MonHoc::all();
        $chapters = Chuong::with('monHoc')->get();
        return view('questions.index', compact('questions', 'subjects', 'chapters'));

    } catch (\Exception $e) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi tìm kiếm: ' . $e->getMessage()
            ], 500);
        }
        return back()->with('error', 'Đã xảy ra lỗi khi tìm kiếm: ' . $e->getMessage());
    }
}




    /**
     * Hiển thị form tạo câu hỏi mới
     */
    public function create()
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $monHocs = MonHoc::all();
        $chuongs = Chuong::all();
        
        return view('questions.create', compact('monHocs', 'chuongs'));
    }

    /**
     * Lưu câu hỏi mới
     */
    public function store(Request $request)
{
    if (Auth::user()->vai_tro == 'hoc_sinh') {
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
    }
    
    $request->validate([
        'ma_chuong' => 'required|exists:Chuong,ma_chuong',
        'noi_dung' => 'required|string',
        'loai_cau_hoi' => 'required|in:trac_nghiem,dien_khuyet',
        'dap_an' => 'required|array|min:1',
        'dap_an.*.noi_dung' => 'required|string',
        'dap_an.*.dung_sai' => 'required|boolean',
    ]);
    
    DB::beginTransaction();
    
    try {
        // Tạo câu hỏi
        $question = CauHoi::create([
            'ma_chuong' => $request->ma_chuong,
            'noi_dung' => $request->noi_dung,
            'loai_cau_hoi' => $request->loai_cau_hoi,
            'nguoi_tao' => Auth::id(),
        ]);
        
        // Xử lý đáp án dựa vào loại câu hỏi
        if ($request->loai_cau_hoi === 'dien_khuyet') {
            // Với câu hỏi điền khuyết, chỉ có 1 đáp án đúng
            DapAn::create([
                'ma_cau_hoi' => $question->ma_cau_hoi,
                'noi_dung' => $request->dap_an[0]['noi_dung'],
                'dung_sai' => true,
            ]);
        } else {
            // Với câu hỏi trắc nghiệm, có nhiều đáp án
            foreach ($request->dap_an as $answer) {
                DapAn::create([
                    'ma_cau_hoi' => $question->ma_cau_hoi,
                    'noi_dung' => $answer['noi_dung'],
                    'dung_sai' => $answer['dung_sai'],
                ]);
            }
        }
        
        DB::commit();
        return redirect()->route('questions.index')->with('success', 'Câu hỏi đã được tạo thành công!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
    }
}


    /**
     * Hiển thị chi tiết câu hỏi
     */
    public function show($id)
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $question = CauHoi::with(['chuong.monHoc', 'dapAn'])->findOrFail($id);
        
        // Nếu là giáo viên, chỉ cho phép xem câu hỏi của họ
        if (Auth::user()->vai_tro == 'giao_vien' && $question->nguoi_tao != Auth::id()) {
            return redirect()->route('questions.index')->with('error', 'Bạn không có quyền xem câu hỏi này!');
        }
        
        return view('questions.show', compact('question'));
    }

    /**
     * Hiển thị form chỉnh sửa câu hỏi
     */
    public function edit($id)
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $question = CauHoi::with('dapAn')->findOrFail($id);
        
        // Nếu là giáo viên, chỉ cho phép chỉnh sửa câu hỏi của họ
        if (Auth::user()->vai_tro == 'giao_vien' && $question->nguoi_tao != Auth::id()) {
            return redirect()->route('questions.index')->with('error', 'Bạn không có quyền chỉnh sửa câu hỏi này!');
        }
        
        $monHocs = MonHoc::all();
        $chuongs = Chuong::all();
        
        return view('questions.edit', compact('question', 'monHocs', 'chuongs'));
    }

    /**
     * Cập nhật câu hỏi
     */
    public function update(Request $request, $id)
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $question = CauHoi::findOrFail($id);
        
        // Nếu là giáo viên, chỉ cho phép chỉnh sửa câu hỏi của họ
        if (Auth::user()->vai_tro == 'giao_vien' && $question->nguoi_tao != Auth::id()) {
            return redirect()->route('questions.index')->with('error', 'Bạn không có quyền chỉnh sửa câu hỏi này!');
        }
        
        $request->validate([
            'ma_chuong' => 'required|exists:Chuong,ma_chuong',
            'noi_dung' => 'required|string',
            'loai_cau_hoi' => 'required|in:trac_nghiem,dien_khuyet',
            'dap_an' => 'required|array|min:1',
            'dap_an.*.noi_dung' => 'required|string',
            'dap_an.*.dung_sai' => 'required|boolean',
        ]);
        
        // Bắt đầu transaction
        DB::beginTransaction();
        
        try {
            // Cập nhật câu hỏi
            $question->update([
                'ma_chuong' => $request->ma_chuong,
                'noi_dung' => $request->noi_dung,
                'loai_cau_hoi' => $request->loai_cau_hoi,
            ]);
            
            // Xóa đáp án cũ
            DapAn::where('ma_cau_hoi', $id)->delete();
            
            // Tạo đáp án mới
            foreach ($request->dap_an as $answer) {
                DapAn::create([
                    'ma_cau_hoi' => $question->ma_cau_hoi,
                    'noi_dung' => $answer['noi_dung'],
                    'dung_sai' => $answer['dung_sai'],
                ]);
            }
            
            DB::commit();
            return redirect()->route('questions.index')->with('success', 'Câu hỏi đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Xóa câu hỏi
     */
    public function destroy($id)
    {
        // Kiểm tra quyền truy cập
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $question = CauHoi::findOrFail($id);
        
        // Nếu là giáo viên, chỉ cho phép xóa câu hỏi của họ
        if (Auth::user()->vai_tro == 'giao_vien' && $question->nguoi_tao != Auth::id()) {
            return redirect()->route('questions.index')->with('error', 'Bạn không có quyền xóa câu hỏi này!');
        }
        
        // Kiểm tra xem câu hỏi có đang được sử dụng trong bài thi nào không
        $isUsed = DB::table('BaiThi_CauHoi')->where('ma_cau_hoi', $id)->exists();
        if ($isUsed) {
            return redirect()->route('questions.index')->with('error', 'Không thể xóa câu hỏi đang được sử dụng trong bài thi!');
        }
        
        // Bắt đầu transaction
        DB::beginTransaction();
        
        try {
            // Xóa đáp án
            DapAn::where('ma_cau_hoi', $id)->delete();
            
            // Xóa câu hỏi
            $question->delete();
            
            DB::commit();
            return redirect()->route('questions.index')->with('success', 'Câu hỏi đã được xóa thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }
    
    /**
     * Lấy danh sách chương theo môn học
     */
    public function getChuongsByMonHoc($maMonHoc)
    {
        // Nếu không đăng nhập hoặc không đúng vai trò, trả về JSON lỗi
        if (!auth()->check() || !in_array(auth()->user()->vai_tro, ['quan_tri', 'giao_vien'])) {
            return response()->json([
                'error' => 'Bạn không có quyền truy cập.',
                'redirect' => route('login.view')
            ], 403);
        }
        try {
            $chuongs = Chuong::where('ma_mon_hoc', $maMonHoc)->get();
            return response()->json($chuongs);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Lấy danh sách câu hỏi theo chương
     */
    public function getQuestionsByChuong($maChuong)
    {
        $questions = CauHoi::where('ma_chuong', $maChuong)
            ->with(['dapAn', 'chuong'])
            ->get();
        return response()->json($questions);
    }
}