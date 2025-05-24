<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MonHoc;
use App\Models\Chuong;

class MonHocChuongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo môn học Toán
        $toan = MonHoc::create([
            'ten_mon_hoc' => 'Toán học',
            'mo_ta' => 'Môn học về toán học cơ bản và nâng cao'
        ]);

        // Tạo các chương cho môn Toán
        Chuong::create([
            'ma_mon_hoc' => $toan->ma_mon_hoc,
            'ten_chuong' => 'Đại số tuyến tính',
            'muc_do' => 'trung_binh',
            'so_thu_tu' => 1,
            'mo_ta' => 'Chương về ma trận, định thức và hệ phương trình tuyến tính'
        ]);

        Chuong::create([
            'ma_mon_hoc' => $toan->ma_mon_hoc,
            'ten_chuong' => 'Giải tích',
            'muc_do' => 'kho',
            'so_thu_tu' => 2,
            'mo_ta' => 'Chương về đạo hàm, tích phân và phương trình vi phân'
        ]);

        Chuong::create([
            'ma_mon_hoc' => $toan->ma_mon_hoc,
            'ten_chuong' => 'Xác suất thống kê',
            'muc_do' => 'trung_binh',
            'so_thu_tu' => 3,
            'mo_ta' => 'Chương về xác suất, biến ngẫu nhiên và thống kê mô tả'
        ]);

        // Tạo môn học Vật lý
        $ly = MonHoc::create([
            'ten_mon_hoc' => 'Vật lý',
            'mo_ta' => 'Môn học về các quy luật vật lý cơ bản'
        ]);

        // Tạo các chương cho môn Vật lý
        Chuong::create([
            'ma_mon_hoc' => $ly->ma_mon_hoc,
            'ten_chuong' => 'Cơ học',
            'muc_do' => 'de',
            'so_thu_tu' => 1,
            'mo_ta' => 'Chương về động học, động lực học và các định luật Newton'
        ]);

        Chuong::create([
            'ma_mon_hoc' => $ly->ma_mon_hoc,
            'ten_chuong' => 'Điện từ học',
            'muc_do' => 'trung_binh',
            'so_thu_tu' => 2,
            'mo_ta' => 'Chương về điện trường, từ trường và cảm ứng điện từ'
        ]);

        Chuong::create([
            'ma_mon_hoc' => $ly->ma_mon_hoc,
            'ten_chuong' => 'Quang học',
            'muc_do' => 'kho',
            'so_thu_tu' => 3,
            'mo_ta' => 'Chương về ánh sáng, giao thoa, nhiễu xạ và phân cực'
        ]);

        // Tạo môn học Tiếng Anh
        $anh = MonHoc::create([
            'ten_mon_hoc' => 'Tiếng Anh',
            'mo_ta' => 'Môn học về ngôn ngữ tiếng Anh'
        ]);

        // Tạo các chương cho môn Tiếng Anh
        Chuong::create([
            'ma_mon_hoc' => $anh->ma_mon_hoc,
            'ten_chuong' => 'Ngữ pháp cơ bản',
            'muc_do' => 'de',
            'so_thu_tu' => 1,
            'mo_ta' => 'Chương về thì, cấu trúc câu và từ loại cơ bản'
        ]);

        Chuong::create([
            'ma_mon_hoc' => $anh->ma_mon_hoc,
            'ten_chuong' => 'Từ vựng chuyên ngành',
            'muc_do' => 'trung_binh',
            'so_thu_tu' => 2,
            'mo_ta' => 'Chương về từ vựng học thuật và chuyên ngành'
        ]);

        Chuong::create([
            'ma_mon_hoc' => $anh->ma_mon_hoc,
            'ten_chuong' => 'Kỹ năng giao tiếp',
            'muc_do' => 'kho',
            'so_thu_tu' => 3,
            'mo_ta' => 'Chương về kỹ năng nói, nghe và giao tiếp trong tình huống thực tế'
        ]);
    }
}