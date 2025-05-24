<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MonHoc;
use App\Models\Chuong;
use App\Models\CauHoi;
use App\Models\DapAn;
use App\Models\BaiThi;
use App\Models\BaiThiCauHoi;

class ResetAndCreateQuestionsExamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Xóa dữ liệu cũ
        $this->resetData();
        
        // Tạo câu hỏi mới
        $this->createQuestions();
        
        // Tạo bài thi mới
        $this->createExams();
    }
    
    /**
     * Xóa dữ liệu cũ
     */
    private function resetData(): void
    {
        // Tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Xóa dữ liệu từ các bảng
        DB::table('BaiThi_CauHoi')->delete();
        DB::table('BaiThi')->delete();
        DB::table('DapAn')->delete();
        DB::table('CauHoi')->delete();
        
        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('Đã xóa dữ liệu cũ');
    }
    
    /**
     * Tạo câu hỏi mới
     */
    private function createQuestions(): void
    {
        // Lấy danh sách chương
        $chuongs = Chuong::all();
        
        foreach ($chuongs as $chuong) {
            // Tạo 10 câu hỏi trắc nghiệm cho mỗi chương
            for ($i = 1; $i <= 10; $i++) {
                $cauHoi = CauHoi::create([
                    'ma_chuong' => $chuong->ma_chuong,
                    'noi_dung' => $this->generateQuestionContent($chuong->monHoc->ten_mon_hoc, $chuong->ten_chuong, $i),
                    'loai_cau_hoi' => 'trac_nghiem',
                    'nguoi_tao' => 1 // Giả sử ID người tạo là 1
                ]);
                
                // Tạo 4 đáp án cho mỗi câu hỏi, 1 đáp án đúng
                $correctAnswer = rand(0, 3);
                for ($j = 0; $j < 4; $j++) {
                    DapAn::create([
                        'ma_cau_hoi' => $cauHoi->ma_cau_hoi,
                        'noi_dung' => 'Đáp án ' . chr(65 + $j) . ' cho câu hỏi ' . $i,
                        'dung_sai' => ($j == $correctAnswer) ? 1 : 0
                    ]);
                }
            }
            
            // Tạo 5 câu hỏi điền khuyết cho mỗi chương
            for ($i = 1; $i <= 5; $i++) {
                $cauHoi = CauHoi::create([
                    'ma_chuong' => $chuong->ma_chuong,
                    'noi_dung' => $this->generateFillInBlankContent($chuong->monHoc->ten_mon_hoc, $chuong->ten_chuong, $i),
                    'loai_cau_hoi' => 'dien_khuyet',
                    'nguoi_tao' => 1 // Giả sử ID người tạo là 1
                ]);
                
                // Tạo 1 đáp án đúng cho câu hỏi điền khuyết
                DapAn::create([
                    'ma_cau_hoi' => $cauHoi->ma_cau_hoi,
                    'noi_dung' => 'Đáp án đúng cho câu điền khuyết ' . $i,
                    'dung_sai' => 1
                ]);
            }
        }
        
        $this->command->info('Đã tạo câu hỏi mới');
    }
    
    /**
     * Tạo bài thi mới
     */
    private function createExams(): void
    {
        // Lấy danh sách môn học
        $monHocs = MonHoc::all();
        
        foreach ($monHocs as $monHoc) {
            // Tạo 2 bài thi cho mỗi môn học
            for ($i = 1; $i <= 2; $i++) {
                $baiThi = BaiThi::create([
                    'ma_mon_hoc' => $monHoc->ma_mon_hoc,
                    'ten_bai_thi' => 'Bài thi ' . $monHoc->ten_mon_hoc . ' - Đợt ' . $i,
                    'tong_so_cau' => 20,
                    'thoi_gian' => 45,
                    'nguoi_tao' => 1 // Giả sử ID người tạo là 1
                ]);
                
                // Lấy các câu hỏi thuộc các chương của môn học này
                $cauHois = CauHoi::whereHas('chuong', function ($query) use ($monHoc) {
                    $query->where('ma_mon_hoc', $monHoc->ma_mon_hoc);
                })->inRandomOrder()->limit(20)->get();
                
                // Thêm câu hỏi vào bài thi
                foreach ($cauHois as $cauHoi) {
                    BaiThiCauHoi::create([
                        'ma_bai_thi' => $baiThi->ma_bai_thi,
                        'ma_cau_hoi' => $cauHoi->ma_cau_hoi
                    ]);
                }
            }
        }
        
        $this->command->info('Đã tạo bài thi mới');
    }
    
    /**
     * Tạo nội dung câu hỏi trắc nghiệm
     */
    private function generateQuestionContent($monHoc, $chuong, $index): string
    {
        $templates = [
            'Toán học' => [
                'Đại số tuyến tính' => [
                    'Cho ma trận A = [[1, 2], [3, 4]]. Tính định thức của ma trận A.',
                    'Giải hệ phương trình: 2x + 3y = 7, 4x - 2y = 10.',
                    'Tìm nghiệm của phương trình ma trận: AX = B, với A = [[2, 1], [3, 4]], B = [[5], [6]].',
                    'Tìm giá trị riêng của ma trận A = [[3, 1], [2, 2]].',
                    'Kiểm tra tính khả nghịch của ma trận A = [[1, 2, 3], [4, 5, 6], [7, 8, 9]].'
                ],
                'Giải tích' => [
                    'Tính đạo hàm của hàm số f(x) = 2x³ - 3x² + 4x - 1.',
                    'Tính tích phân: ∫(0 đến 1) x²dx.',
                    'Tìm cực trị của hàm số f(x) = x³ - 3x² + 3x - 1.',
                    'Giải phương trình vi phân: y\' + 2y = e^x.',
                    'Tính giới hạn: lim(x→0) (sin(x)/x).'
                ],
                'Xác suất thống kê' => [
                    'Tính xác suất rút được quân K trong một bộ bài 52 lá.',
                    'Tính kỳ vọng của biến ngẫu nhiên X có phân phối chuẩn N(2, 4).',
                    'Tính phương sai của biến ngẫu nhiên X có phân phối nhị thức B(10, 0.3).',
                    'Tính hệ số tương quan giữa hai biến ngẫu nhiên X và Y.',
                    'Kiểm định giả thuyết H₀: μ = 5 với mức ý nghĩa α = 0.05.'
                ]
            ],
            'Vật lý' => [
                'Cơ học' => [
                    'Một vật chuyển động với vận tốc 10 m/s. Tính quãng đường đi được sau 5 giây.',
                    'Một vật có khối lượng 2 kg chuyển động với gia tốc 5 m/s². Tính lực tác dụng lên vật.',
                    'Tính công thực hiện khi di chuyển một vật nặng 10 kg lên độ cao 5 m.',
                    'Một vật được ném thẳng đứng lên cao với vận tốc ban đầu 20 m/s. Tính độ cao cực đại.',
                    'Tính động năng của một vật có khối lượng 5 kg chuyển động với vận tốc 10 m/s.'
                ],
                'Điện từ học' => [
                    'Một dòng điện có cường độ 2 A chạy qua một điện trở 10 Ω. Tính công suất tiêu thụ.',
                    'Tính điện trở tương đương của mạch gồm hai điện trở 5 Ω và 10 Ω mắc song song.',
                    'Tính cường độ từ trường tại tâm của một dòng điện tròn.',
                    'Tính suất điện động cảm ứng trong một cuộn dây khi từ thông qua nó thay đổi.',
                    'Tính điện dung của một tụ điện phẳng có diện tích bản cực là 10 cm² và khoảng cách giữa hai bản là 1 mm.'
                ],
                'Quang học' => [
                    'Tính góc khúc xạ khi ánh sáng truyền từ không khí vào nước với góc tới 30°.',
                    'Tính bước sóng của ánh sáng có tần số 5 × 10¹⁴ Hz.',
                    'Tính vị trí của ảnh qua thấu kính hội tụ có tiêu cự 10 cm khi vật cách thấu kính 15 cm.',
                    'Mô tả hiện tượng giao thoa ánh sáng trong thí nghiệm Young.',
                    'Tính năng lượng của một photon có bước sóng 500 nm.'
                ]
            ],
            'Tiếng Anh' => [
                'Ngữ pháp cơ bản' => [
                    'Choose the correct form of the verb: She _____ to school every day.',
                    'Identify the correct tense: By the time we arrived, the movie _____ (start).',
                    'Select the appropriate article: I saw _____ interesting movie last night.',
                    'Choose the correct preposition: The book is _____ the table.',
                    'Identify the correct comparative form: This exercise is _____ than the previous one.'
                ],
                'Từ vựng chuyên ngành' => [
                    'Choose the synonym of "allocate" in a business context.',
                    'Select the word that best completes the sentence: The company\'s annual _____ showed a profit increase.',
                    'Identify the correct technical term: The process of converting data into a code to prevent unauthorized access is called _____.',
                    'Choose the appropriate medical term: The doctor diagnosed the patient with _____ after examining the symptoms.',
                    'Select the correct legal terminology: The _____ is responsible for representing the defendant in court.'
                ],
                'Kỹ năng giao tiếp' => [
                    'Choose the most appropriate response to: "Would you mind if I opened the window?"',
                    'Select the best way to politely disagree in a business meeting.',
                    'Identify the most effective way to start a formal presentation.',
                    'Choose the appropriate expression to use when making a suggestion.',
                    'Select the most polite way to interrupt someone during a conversation.'
                ]
            ]
        ];
        
        // Nếu không có template cho môn học hoặc chương, tạo câu hỏi chung
        if (!isset($templates[$monHoc]) || !isset($templates[$monHoc][$chuong])) {
            return "Câu hỏi số {$index} về {$chuong} trong môn {$monHoc}.";
        }
        
        $questionTemplates = $templates[$monHoc][$chuong];
        $questionIndex = $index % count($questionTemplates);
        
        return $questionTemplates[$questionIndex];
    }
    
    /**
     * Tạo nội dung câu hỏi điền khuyết
     */
    private function generateFillInBlankContent($monHoc, $chuong, $index): string
    {
        $templates = [
            'Toán học' => [
                'Đại số tuyến tính' => [
                    'Định thức của ma trận A là ___.',
                    'Hạng của ma trận là số ___.',
                    'Ma trận nghịch đảo của A ký hiệu là ___.',
                    'Hệ phương trình tuyến tính có dạng ___ = ___.',
                    'Không gian vector là một tập hợp các ___ thỏa mãn các tiên đề nhất định.'
                ],
                'Giải tích' => [
                    'Đạo hàm của hàm số f(x) = x² là ___.',
                    'Tích phân của hàm số f(x) = 2x là ___.',
                    'Giới hạn của hàm số f(x) = sin(x)/x khi x tiến đến 0 là ___.',
                    'Chuỗi Taylor của hàm số e^x quanh x = 0 là ___.',
                    'Điều kiện cần để hàm số f(x) có cực trị tại x₀ là ___.'
                ],
                'Xác suất thống kê' => [
                    'Xác suất của một biến cố luôn nằm trong khoảng ___.',
                    'Kỳ vọng của biến ngẫu nhiên X ký hiệu là ___.',
                    'Phương sai của biến ngẫu nhiên X ký hiệu là ___.',
                    'Hai biến cố A và B được gọi là độc lập nếu P(A∩B) = ___.',
                    'Phân phối chuẩn được xác định bởi hai tham số là ___ và ___.'
                ]
            ],
            'Vật lý' => [
                'Cơ học' => [
                    'Định luật II Newton: F = ___.',
                    'Công thức tính động năng: E = ___.',
                    'Định luật vạn vật hấp dẫn: F = G × ___ × ___ / r².',
                    'Công thức tính gia tốc rơi tự do: a = ___.',
                    'Định luật bảo toàn động lượng: ___ = hằng số.'
                ],
                'Điện từ học' => [
                    'Định luật Ohm: I = ___.',
                    'Công thức tính điện trở của dây dẫn: R = ρ × ___ / S.',
                    'Định luật Coulomb: F = k × ___ × ___ / r².',
                    'Công thức tính công suất điện: P = ___.',
                    'Định luật Faraday về cảm ứng điện từ: ε = - ___.'
                ],
                'Quang học' => [
                    'Định luật khúc xạ ánh sáng: n₁ × sin(i) = ___.',
                    'Công thức tính tiêu cự của thấu kính: 1/f = ___ + ___.',
                    'Công thức tính bước sóng: λ = ___.',
                    'Năng lượng của photon: E = ___.',
                    'Hiện tượng giao thoa ánh sáng xảy ra khi ___.'
                ]
            ],
            'Tiếng Anh' => [
                'Ngữ pháp cơ bản' => [
                    'The present continuous tense is formed with the verb ___ and the -ing form.',
                    'Countable nouns can be ___ while uncountable nouns cannot.',
                    'The past perfect tense is used to describe an action that happened ___ another past action.',
                    'Adjectives usually come ___ the noun they describe in English.',
                    'Modal verbs like "can" and "must" are followed by the ___ form of the verb.'
                ],
                'Từ vựng chuyên ngành' => [
                    'In business English, ROI stands for ___.',
                    'The medical term for high blood pressure is ___.',
                    'In computer science, CPU stands for ___.',
                    'In legal terminology, a ___ is a formal written statement sworn to be true.',
                    'In academic writing, a ___ is a brief summary of a research article or thesis.'
                ],
                'Kỹ năng giao tiếp' => [
                    'When making a formal introduction, you should say "___" instead of "Hi".',
                    'To politely disagree in a meeting, you can say "I see your point, but ___".',
                    'When ending a business email, a common formal closing is "___".',
                    'To interrupt politely in a conversation, you can say "___".',
                    'When giving a presentation, you should begin with a ___ to outline what you will discuss.'
                ]
            ]
        ];
        
        // Nếu không có template cho môn học hoặc chương, tạo câu hỏi chung
        if (!isset($templates[$monHoc]) || !isset($templates[$monHoc][$chuong])) {
            return "Hoàn thành câu sau về {$chuong} trong môn {$monHoc}: ... là ___.";
        }
        
        $questionTemplates = $templates[$monHoc][$chuong];
        $questionIndex = $index % count($questionTemplates);
        
        return $questionTemplates[$questionIndex];
    }
}