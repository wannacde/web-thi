<?php
namespace App\Http\Controllers;

use App\Models\BaiThi;
use App\Models\CauHoi;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // Tạo bài thi mới
    public function createExam(Request $request)
    {
        $request->validate([
            'ma_mon_hoc' => 'required|exists:MonHoc,ma_mon_hoc',
            'ten_bai_thi' => 'required|string|max:255',
            'tong_so_cau' => 'required|integer',
            'thoi_gian' => 'required|integer',
            'nguoi_tao' => 'required|exists:NguoiDung,ma_nguoi_dung',
        ]);

        $exam = BaiThi::create($request->all());

        return response()->json(['message' => 'Bài thi đã được tạo thành công!', 'exam' => $exam], 201);
    }

    // Lấy danh sách bài thi
    public function getExams()
    {
        $exams = BaiThi::with('monHoc')->get();
        return response()->json($exams, 200);
    }

    // Lấy thông tin bài thi theo ID
    public function getExam($id)
    {
        $exam = BaiThi::with(['monHoc', 'cauHoi'])->find($id);
        if (!$exam) {
            return response()->json(['message' => 'Bài thi không tồn tại!'], 404);
        }
        return response()->json($exam, 200);
    }

    // Cập nhật bài thi
    public function updateExam(Request $request, $id)
    {
        $request->validate([
            'ten_bai_thi' => 'sometimes|required|string|max:255',
            'tong_so_cau' => 'sometimes|required|integer',
            'thoi_gian' => 'sometimes|required|integer',
        ]);

        $exam = BaiThi::find($id);
        if (!$exam) {
            return response()->json(['message' => 'Bài thi không tồn tại!'], 404);
        }

        $exam->update($request->all());
        return response()->json(['message' => 'Bài thi đã được cập nhật thành công!', 'exam' => $exam], 200);
    }

    // Xóa bài thi
    public function deleteExam($id)
    {
        $exam = BaiThi::find($id);
        if (!$exam) {
            return response()->json(['message' => 'Bài thi không tồn tại!'], 404);
        }

        $exam->delete();
        return response()->json(['message' => 'Bài thi đã được xóa thành công!'], 200);
    }
}


