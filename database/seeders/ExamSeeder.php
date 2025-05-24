<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExamSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách môn học
        $monhoc = DB::table('monhoc')->get();
        
        if ($monhoc->isEmpty()) {
            echo "Không có môn học nào trong cơ sở dữ liệu.\n";
            return;
        }
        
        // Tạo 10 bài thi mẫu
        for ($i = 1; $i <= 10; $i++) {
            // Chọn ngẫu nhiên một môn học
            $mon = $monhoc->random();
            
            // Lấy ID của môn học
            $monId = $mon->ma_mon_hoc ?? $mon->ma_mon ?? $mon->id;
            
            // Lấy tên môn học
            $tenMon = $mon->ten_mon_hoc ?? $mon->ten_mon ?? $mon->ten;
            
            // Tạo bài thi
            $examId = DB::table('baithi')->insertGetId([
                'ma_mon_hoc' => $monId,
                'ten_bai_thi' => 'Bài thi ' . $tenMon . ' - Đợt ' . $i,
                'thoi_gian' => rand(30, 90),
                'tong_so_cau' => rand(20, 40),
                'ngay_tao' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
            
            // Lấy các câu hỏi thuộc chương của môn học này
            $chuongs = DB::table('chuong')
                ->where('ma_mon_hoc', $monId)
                ->pluck('ma_chuong');
                
            if ($chuongs->isEmpty()) {
                continue;
            }
            
            $questions = DB::table('cauhoi')
                ->whereIn('ma_chuong', $chuongs)
                ->inRandomOrder()
                ->limit(rand(20, 40))
                ->get();
            
            // Thêm câu hỏi vào bài thi
            foreach ($questions as $question) {
                DB::table('baithi_cauhoi')->insert([
                    'ma_bai_thi' => $examId,
                    'ma_cau_hoi' => $question->ma_cau_hoi,
                ]);
            }
        }
    }
}