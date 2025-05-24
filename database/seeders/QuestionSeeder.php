<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách môn học
        $monhoc = DB::table('monhoc')->get();
        
        // Lấy danh sách chương
        $chuongs = DB::table('chuong')->get();
        
        if ($monhoc->isEmpty()) {
            echo "Không có môn học nào trong cơ sở dữ liệu.\n";
            return;
        }
        
        if ($chuongs->isEmpty()) {
            echo "Không có chương nào trong cơ sở dữ liệu.\n";
            return;
        }
        
        // Tạo câu hỏi cho từng môn học
        foreach ($monhoc as $mon) {
            // Lấy ID của môn học
            $monId = $mon->ma_mon_hoc ?? $mon->ma_mon ?? $mon->id;
            
            // Lọc các chương thuộc môn học này
            $monChuongs = $chuongs->filter(function($chuong) use ($monId) {
                $chuongMonId = $chuong->ma_mon_hoc ?? $chuong->ma_mon ?? $chuong->mon_id;
                return $chuongMonId == $monId;
            });
            
            if ($monChuongs->isEmpty()) {
                echo "Không có chương nào cho môn học ID: $monId\n";
                continue;
            }
            
            $this->createQuestionsForSubject($mon, $monChuongs, $monId);
        }
    }
    
    private function createQuestionsForSubject($monhoc, $chuongs, $monId)
    {
        // Số câu hỏi cần tạo cho mỗi môn học
        $questionsPerSubject = 70;
        
        // Lấy tên môn học
        $tenMon = $monhoc->ten_mon_hoc ?? $monhoc->ten_mon ?? $monhoc->ten;
        
        // Danh sách câu hỏi theo môn học
        $questions = $this->getQuestionsBySubject($tenMon, $questionsPerSubject);
        
        // Tạo câu hỏi
        foreach ($questions as $index => $question) {
            // Chọn ngẫu nhiên một chương thuộc môn học
            if ($chuongs->count() == 0) {
                continue;
            }
            
            $chuong = $chuongs->random();
            $chuongId = $chuong->ma_chuong ?? $chuong->id;
            
            // Thêm câu hỏi
            $cauhoiId = DB::table('cauhoi')->insertGetId([
                'ma_chuong' => $chuongId,
                'noi_dung' => $question['question'],
                'loai_cau_hoi' => 'trac_nghiem',
            ]);
            
            // Thêm các đáp án
            foreach ($question['answers'] as $answer) {
                DB::table('dapan')->insert([
                    'ma_cau_hoi' => $cauhoiId,
                    'noi_dung' => $answer['text'],
                    'dung_sai' => $answer['correct'] ? 1 : 0,
                ]);
            }
        }
    }
    
    private function getQuestionsBySubject($subject, $count)
    {
        $questions = [];
        
        switch (strtolower($subject)) {
            case 'toán':
                $questions = $this->getMathQuestions($count);
                break;
            case 'vật lý':
                $questions = $this->getPhysicsQuestions($count);
                break;
            case 'hóa học':
                $questions = $this->getChemistryQuestions($count);
                break;
            default:
                $questions = $this->getGeneralQuestions($count);
        }
        
        return $questions;
    }
    
    private function getMathQuestions($count)
    {
        $questions = [];
        
        $templates = [
            [
                'question' => 'Tính giá trị của biểu thức: %s',
                'expressions' => [
                    '2x + 3 = 7', '3x - 5 = 10', 'x² + 2x - 3 = 0',
                    '2x² - 5x + 2 = 0', 'log₂(x) = 3', '2^x = 8',
                    'sin(x) = 0.5', 'cos(x) = 0', 'tan(x) = 1',
                    '√x + 2 = 5', '|2x - 3| = 5', '3x + 4y = 10, 2x - y = 5'
                ]
            ],
            [
                'question' => 'Giải phương trình: %s',
                'expressions' => [
                    'x² - 4 = 0', '2x² - 5x + 2 = 0', 'x³ - 6x² + 11x - 6 = 0',
                    'log(x) + log(x+3) = log(4x)', 'e^x = 5', '2sin²(x) - sin(x) - 1 = 0',
                    '√(2x+1) - √(x-1) = 1', '|3x - 2| = |x + 1|', 'x^4 - 5x² + 4 = 0'
                ]
            ],
            [
                'question' => 'Tìm đạo hàm của hàm số: f(x) = %s',
                'expressions' => [
                    '2x³ - 3x² + 4x - 1', 'sin(x) + cos(x)', 'e^x * ln(x)',
                    'x / (x² + 1)', '√(x² + 1)', 'log₃(x²)', 
                    'tan(2x)', 'x * e^x', 'sin²(x)'
                ]
            ]
        ];
        
        for ($i = 0; $i < $count; $i++) {
            $template = $templates[array_rand($templates)];
            $expression = $template['expressions'][array_rand($template['expressions'])];
            $questionText = sprintf($template['question'], $expression);
            
            $answers = $this->generateRandomAnswers();
            
            $questions[] = [
                'question' => $questionText,
                'answers' => $answers
            ];
        }
        
        return $questions;
    }
    
    private function getPhysicsQuestions($count)
    {
        $questions = [];
        
        $templates = [
            [
                'question' => 'Một vật chuyển động với vận tốc %s m/s. Tính quãng đường đi được sau %s giây.',
                'params' => [
                    [10, 5], [15, 3], [20, 4], [25, 2], [30, 6]
                ]
            ],
            [
                'question' => 'Một vật có khối lượng %s kg chuyển động với gia tốc %s m/s². Tính lực tác dụng lên vật.',
                'params' => [
                    [2, 5], [3, 4], [5, 2], [10, 1], [1, 10]
                ]
            ],
            [
                'question' => 'Một dòng điện có cường độ %s A chạy qua một điện trở %s Ω. Tính công suất tiêu thụ.',
                'params' => [
                    [2, 10], [5, 4], [1, 20], [3, 6], [0.5, 100]
                ]
            ]
        ];
        
        for ($i = 0; $i < $count; $i++) {
            $template = $templates[array_rand($templates)];
            $params = $template['params'][array_rand($template['params'])];
            $questionText = vsprintf($template['question'], $params);
            
            $answers = $this->generateRandomAnswers();
            
            $questions[] = [
                'question' => $questionText,
                'answers' => $answers
            ];
        }
        
        return $questions;
    }
    
    private function getChemistryQuestions($count)
    {
        $questions = [];
        
        $templates = [
            'Viết phương trình hóa học của phản ứng giữa %s và %s.',
            'Tính khối lượng của %s cần dùng để điều chế %s gam %s.',
            'Nêu tính chất hóa học của %s.',
            'Phân tích cấu tạo phân tử của %s.',
            'Giải thích hiện tượng xảy ra khi cho %s tác dụng với %s.'
        ];
        
        $chemicals = [
            'H₂O', 'NaCl', 'HCl', 'NaOH', 'H₂SO₄', 'CaCO₃', 'Fe₂O₃',
            'CH₄', 'C₂H₅OH', 'CO₂', 'NH₃', 'O₂', 'N₂', 'Cl₂'
        ];
        
        for ($i = 0; $i < $count; $i++) {
            $template = $templates[array_rand($templates)];
            
            // Tạo câu hỏi bằng cách thay thế các tham số
            $questionText = $template;
            while (strpos($questionText, '%s') !== false) {
                $questionText = preg_replace('/%s/', $chemicals[array_rand($chemicals)], $questionText, 1);
            }
            
            // Nếu là câu hỏi về khối lượng, thêm một số ngẫu nhiên
            if (strpos($template, 'gam') !== false) {
                $questionText = preg_replace('/gam/', (rand(10, 100) . ' gam'), $questionText);
            }
            
            $answers = $this->generateRandomAnswers();
            
            $questions[] = [
                'question' => $questionText,
                'answers' => $answers
            ];
        }
        
        return $questions;
    }
    
    private function getGeneralQuestions($count)
    {
        // Fallback nếu không có môn học cụ thể
        $questions = [];
        
        for ($i = 0; $i < $count; $i++) {
            $questions[] = [
                'question' => 'Câu hỏi tổng quát số ' . ($i + 1),
                'answers' => $this->generateRandomAnswers()
            ];
        }
        
        return $questions;
    }
    
    private function generateRandomAnswers()
    {
        $answers = [];
        $correctIndex = rand(0, 3);
        
        for ($i = 0; $i < 4; $i++) {
            $answers[] = [
                'text' => 'Đáp án ' . chr(65 + $i),
                'correct' => ($i == $correctIndex)
            ];
        }
        
        return $answers;
    }
}