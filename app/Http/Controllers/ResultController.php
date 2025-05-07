<?php
namespace App\Http\Controllers;

use App\Models\KetQuaBaiThi;
use App\Models\TraLoiNguoiDung;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    // Lưu kết quả bài thi
    public function saveResult(Request $request)
    {
        $request->validate([
            'ma_bai_thi' => 'required|exists:BaiThi,ma_bai_thi',
            'ma_nguoi_dung' => 'required|exists:NguoiDung,ma_nguoi_dung',
            'diem' => 'required|numeric',
            'tra_lois' => 'required|array',
        ]);

        // Tạo kết quả bài thi
        $result = KetQuaBaiThi::create([
            'ma_bai_thi' => $request->ma_bai_thi,
            'ma_nguoi_dung' => $request->ma_nguoi_dung,
            'diem' => $request->diem,
        ]);

        // Lưu các câu trả lời của người dùng
        foreach ($request->tra_lois as $tra_loi) {
            TraLoiNguoiDung::create([
                'ma_ket_qua' => $result->ma_ket_qua,
                'ma_cau_hoi' => $tra_loi['ma_cau_hoi'],
                'dap_an_chon' => $tra_loi['dap_an_chon'],
                'dung_sai' => $tra_loi['dung_sai'],
            ]);
        }

        return response()->json(['message' => 'Kết quả bài thi đã được lưu thành công!', 'result' => $result], 201);
    }

    // Lấy kết quả bài thi của người dùng
    public function getResults($ma_nguoi_dung)
    {
        $results = KetQuaBaiThi::with(['baiThi', 'traLoiNguoiDung'])
            ->where('ma_nguoi_dung', $ma_nguoi_dung)
            ->get();

        if ($results->isEmpty()) {
            return response()->json(['message' => 'Không tìm thấy kết quả nào cho người dùng này.'], 404);
        }

        return response()->json($results, 200);
    }

    // Lấy kết quả bài thi theo ID
    public function getResult($id)
    {
        $result = KetQuaBaiThi::with(['baiThi', 'traLoiNguoiDung'])->find($id);
        if (!$result) {
            return response()->json(['message' => 'Kết quả bài thi không tồn tại!'], 404);
        }
        return response()->json($result, 200);
    }
}

