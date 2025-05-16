<?php

namespace App\Http\Controllers;

use App\Models\MonHoc;
use App\Models\Chuong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * Hiển thị danh sách môn học
     */
    public function index()
    {
        // Chỉ admin và giáo viên mới có quyền quản lý môn học
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $subjects = MonHoc::withCount('chuong')->get();
        return view('subjects.index', compact('subjects'));
    }

    /**
     * Hiển thị form tạo môn học mới
     */
    public function create()
    {
        // Chỉ admin mới có quyền tạo môn học
        if (Auth::user()->vai_tro != 'quan_tri') {
            return redirect()->route('subjects.index')->with('error', 'Bạn không có quyền tạo môn học mới!');
        }
        
        return view('subjects.create');
    }

    /**
     * Lưu môn học mới
     */
    public function store(Request $request)
    {
        // Chỉ admin mới có quyền tạo môn học
        if (Auth::user()->vai_tro != 'quan_tri') {
            return redirect()->route('subjects.index')->with('error', 'Bạn không có quyền tạo môn học mới!');
        }
        
        $request->validate([
            'ten_mon_hoc' => 'required|string|max:100|unique:MonHoc',
            'mo_ta' => 'nullable|string',
        ]);
        
        MonHoc::create([
            'ten_mon_hoc' => $request->ten_mon_hoc,
            'mo_ta' => $request->mo_ta,
        ]);
        
        return redirect()->route('subjects.index')->with('success', 'Môn học đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết môn học
     */
    public function show($id)
    {
        // Chỉ admin và giáo viên mới có quyền xem chi tiết môn học
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $subject = MonHoc::with('chuong')->findOrFail($id);
        return view('subjects.show', compact('subject'));
    }

    /**
     * Hiển thị form chỉnh sửa môn học
     */
    public function edit($id)
    {
        // Chỉ admin mới có quyền chỉnh sửa môn học
        if (Auth::user()->vai_tro != 'quan_tri') {
            return redirect()->route('subjects.index')->with('error', 'Bạn không có quyền chỉnh sửa môn học!');
        }
        
        $subject = MonHoc::findOrFail($id);
        return view('subjects.edit', compact('subject'));
    }

    /**
     * Cập nhật môn học
     */
    public function update(Request $request, $id)
    {
        // Chỉ admin mới có quyền chỉnh sửa môn học
        if (Auth::user()->vai_tro != 'quan_tri') {
            return redirect()->route('subjects.index')->with('error', 'Bạn không có quyền chỉnh sửa môn học!');
        }
        
        $request->validate([
            'ten_mon_hoc' => 'required|string|max:100|unique:MonHoc,ten_mon_hoc,'.$id.',ma_mon_hoc',
            'mo_ta' => 'nullable|string',
        ]);
        
        $subject = MonHoc::findOrFail($id);
        $subject->update([
            'ten_mon_hoc' => $request->ten_mon_hoc,
            'mo_ta' => $request->mo_ta,
        ]);
        
        return redirect()->route('subjects.index')->with('success', 'Môn học đã được cập nhật thành công!');
    }

    /**
     * Xóa môn học
     */
    public function destroy($id)
    {
        // Chỉ admin mới có quyền xóa môn học
        if (Auth::user()->vai_tro != 'quan_tri') {
            return redirect()->route('subjects.index')->with('error', 'Bạn không có quyền xóa môn học!');
        }
        
        // Kiểm tra xem môn học có đang được sử dụng không
        $hasChapters = Chuong::where('ma_mon_hoc', $id)->exists();
        $hasExams = \App\Models\BaiThi::where('ma_mon_hoc', $id)->exists();
        
        if ($hasChapters || $hasExams) {
            return redirect()->route('subjects.index')->with('error', 'Không thể xóa môn học đang được sử dụng!');
        }
        
        MonHoc::destroy($id);
        return redirect()->route('subjects.index')->with('success', 'Môn học đã được xóa thành công!');
    }
    
    /**
     * Quản lý chương của môn học
     */
    public function manageChapters($id)
    {
        // Chỉ admin và giáo viên mới có quyền quản lý chương
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $subject = MonHoc::with(['chuong' => function($query) {
            $query->orderBy('so_thu_tu', 'asc');
        }])->findOrFail($id);
        
        return view('subjects.chapters', compact('subject'));
    }
    
    /**
     * Thêm chương mới cho môn học
     */
    public function storeChapter(Request $request, $id)
    {
        // Chỉ admin và giáo viên mới có quyền thêm chương
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $request->validate([
            'ten_chuong' => 'required|string|max:100',
            'muc_do' => 'required|in:de,trung_binh,kho',
            'so_thu_tu' => 'required|integer|min:1',
            'mo_ta' => 'nullable|string',
        ]);
        
        Chuong::create([
            'ma_mon_hoc' => $id,
            'ten_chuong' => $request->ten_chuong,
            'muc_do' => $request->muc_do,
            'so_thu_tu' => $request->so_thu_tu,
            'mo_ta' => $request->mo_ta,
        ]);
        
        return redirect()->route('subjects.chapters', $id)->with('success', 'Chương đã được thêm thành công!');
    }
    
    /**
     * Cập nhật chương
     */
    public function updateChapter(Request $request, $id, $chapterId)
    {
        // Chỉ admin và giáo viên mới có quyền cập nhật chương
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        $request->validate([
            'ten_chuong' => 'required|string|max:100',
            'muc_do' => 'required|in:de,trung_binh,kho',
            'so_thu_tu' => 'required|integer|min:1',
            'mo_ta' => 'nullable|string',
        ]);
        
        $chapter = Chuong::findOrFail($chapterId);
        $chapter->update([
            'ten_chuong' => $request->ten_chuong,
            'muc_do' => $request->muc_do,
            'so_thu_tu' => $request->so_thu_tu,
            'mo_ta' => $request->mo_ta,
        ]);
        
        return redirect()->route('subjects.chapters', $id)->with('success', 'Chương đã được cập nhật thành công!');
    }
    
    /**
     * Xóa chương
     */
    public function destroyChapter($id, $chapterId)
    {
        // Chỉ admin và giáo viên mới có quyền xóa chương
        if (Auth::user()->vai_tro == 'hoc_sinh') {
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này!');
        }
        
        // Kiểm tra xem chương có đang được sử dụng không
        $hasQuestions = \App\Models\CauHoi::where('ma_chuong', $chapterId)->exists();
        
        if ($hasQuestions) {
            return redirect()->route('subjects.chapters', $id)->with('error', 'Không thể xóa chương đang có câu hỏi!');
        }
        
        Chuong::destroy($chapterId);
        return redirect()->route('subjects.chapters', $id)->with('success', 'Chương đã được xóa thành công!');
    }
}