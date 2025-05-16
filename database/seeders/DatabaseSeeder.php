<?php
// File: database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Tạo người dùng mẫu
        DB::table('NguoiDung')->insert([
            [
                'ten_dang_nhap' => 'admin',
                'mat_khau' => Hash::make('admin123'),
                'ho_ten' => 'Quản trị viên',
                'email' => 'admin@example.com',
                'vai_tro' => 'quan_tri',
                'ngay_tao' => now()
            ],
            [
                'ten_dang_nhap' => 'giaovien',
                'mat_khau' => Hash::make('giaovien123'),
                'ho_ten' => 'Giáo viên',
                'email' => 'giaovien@example.com',
                'vai_tro' => 'giao_vien',
                'ngay_tao' => now()
            ],
            [
                'ten_dang_nhap' => 'hocsinh',
                'mat_khau' => Hash::make('hocsinh123'),
                'ho_ten' => 'Học sinh',
                'email' => 'hocsinh@example.com',
                'vai_tro' => 'hoc_sinh',
                'ngay_tao' => now()
            ]
        ]);

        // Tạo môn học mẫu
        DB::table('MonHoc')->insert([
            [
                'ten_mon_hoc' => 'Toán học',
                'mo_ta' => 'Môn học về toán'
            ],
            [
                'ten_mon_hoc' => 'Vật lý',
                'mo_ta' => 'Môn học về vật lý'
            ],
            [
                'ten_mon_hoc' => 'Hóa học',
                'mo_ta' => 'Môn học về hóa học'
            ]
        ]);

        // Tạo chương mẫu
        DB::table('Chuong')->insert([
            [
                'ma_mon_hoc' => 1,
                'ten_chuong' => 'Đại số',
                'muc_do' => 'trung_binh',
                'so_thu_tu' => 1,
                'mo_ta' => 'Chương về đại số'
            ],
            [
                'ma_mon_hoc' => 1,
                'ten_chuong' => 'Hình học',
                'muc_do' => 'kho',
                'so_thu_tu' => 2,
                'mo_ta' => 'Chương về hình học'
            ],
            [
                'ma_mon_hoc' => 2,
                'ten_chuong' => 'Cơ học',
                'muc_do' => 'de',
                'so_thu_tu' => 1,
                'mo_ta' => 'Chương về cơ học'
            ]
        ]);

        // Tạo câu hỏi mẫu
        DB::table('CauHoi')->insert([
            [
                'ma_chuong' => 1,
                'noi_dung' => 'Phương trình bậc 2 có dạng tổng quát là gì?',
                'loai_cau_hoi' => 'trac_nghiem',
                'nguoi_tao' => 2,
                'ngay_tao' => now()
            ],
            [
                'ma_chuong' => 1,
                'noi_dung' => 'Tính 2 + 2 = ?',
                'loai_cau_hoi' => 'dien_khuyet',
                'nguoi_tao' => 2,
                'ngay_tao' => now()
            ],
            [
                'ma_chuong' => 3,
                'noi_dung' => 'Công thức tính vận tốc là gì?',
                'loai_cau_hoi' => 'trac_nghiem',
                'nguoi_tao' => 2,
                'ngay_tao' => now()
            ]
        ]);

        // Tạo đáp án mẫu
        DB::table('DapAn')->insert([
            [
                'ma_cau_hoi' => 1,
                'noi_dung' => 'ax^2 + bx + c = 0',
                'dung_sai' => 1
            ],
            [
                'ma_cau_hoi' => 1,
                'noi_dung' => 'ax + b = 0',
                'dung_sai' => 0
            ],
            [
                'ma_cau_hoi' => 1,
                'noi_dung' => 'ax^3 + bx^2 + cx + d = 0',
                'dung_sai' => 0
            ],
            [
                'ma_cau_hoi' => 2,
                'noi_dung' => '4',
                'dung_sai' => 1
            ],
            [
                'ma_cau_hoi' => 3,
                'noi_dung' => 'v = s/t',
                'dung_sai' => 1
            ],
            [
                'ma_cau_hoi' => 3,
                'noi_dung' => 'v = s*t',
                'dung_sai' => 0
            ],
            [
                'ma_cau_hoi' => 3,
                'noi_dung' => 'v = t/s',
                'dung_sai' => 0
            ]
        ]);

        // Tạo bài thi mẫu
        DB::table('BaiThi')->insert([
            [
                'ma_mon_hoc' => 1,
                'ten_bai_thi' => 'Kiểm tra Toán học',
                'tong_so_cau' => 2,
                'thoi_gian' => 30,
                'nguoi_tao' => 2,
                'ngay_tao' => now()
            ],
            [
                'ma_mon_hoc' => 2,
                'ten_bai_thi' => 'Kiểm tra Vật lý',
                'tong_so_cau' => 1,
                'thoi_gian' => 15,
                'nguoi_tao' => 2,
                'ngay_tao' => now()
            ]
        ]);

        // Liên kết bài thi với câu hỏi
        DB::table('BaiThi_CauHoi')->insert([
            [
                'ma_bai_thi' => 1,
                'ma_cau_hoi' => 1
            ],
            [
                'ma_bai_thi' => 1,
                'ma_cau_hoi' => 2
            ],
            [
                'ma_bai_thi' => 2,
                'ma_cau_hoi' => 3
            ]
        ]);
    }
}
