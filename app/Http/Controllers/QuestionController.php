<?php
namespace App\Http\Controllers;

use App\Models\CauHoi;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Tạo câu hỏi mới
    public function createQuestion(Request $request)
    {
        $request->validate([
            'ma_chuong' => 'required|exists:Chuong,ma_chuong',
            'noi_dung' => 'required|string',
            'loai_cau_hoi' => 'required|in:trac_nghiem,dien_khuyet',
            'nguoi_tao' => 'required|exists:NguoiDung,ma_nguoi_dung',
        ]);

        $question = CauHoi::create($request->all());

        return response()->json(['message' => 'Câu hỏi đã được tạo thành công!', 'question' => $question], 201);
    }

    // Lấy danh sách câu hỏi
    public function getQuestions()
    {
        $questions = CauHoi::with('chuong')->get();
        return response()->json($questions, 200);
    }

    // Lấy thông tin câu hỏi theo ID
    public function getQuestion($id)
    {
        $question = CauHoi::with('chuong')->find($id);
        if (!$question) {
            return response()->json(['message' => 'Câu hỏi không tồn tại!'], 404);
        }
        return response()->json($question, 200);
    }

    // Cập nhật câu hỏi
    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'noi_dung' => 'sometimes|required|string',
            'loai_cau_hoi' => 'sometimes|required|in:trac_nghiem,dien_khuyet',
            'ma_chuong' => 'sometimes|required|exists:Chuong,ma_chuong',
        ]);

        $question = CauHoi::find($id);
        if (!$question) {
            return response()->json(['message' => 'Câu hỏi không tồn tại!'], 404);
        }

        $question->update($request->all());
        return response()->json(['message' => 'Câu hỏi đã được cập nhật thành công!', 'question' => $question], 200);
    }

    // Xóa câu hỏi
    public function deleteQuestion($id)
    {
        $question = CauHoi::find($id);
        if (!$question) {
            return response()->json(['message' => 'Câu hỏi không tồn tại!'], 404);
        }

        $question->delete();
        return response()->json(['message' => 'Câu hỏi đã được xóa thành công!'], 200);
    }
}

