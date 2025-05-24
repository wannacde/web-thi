<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\BaiThi;
use Illuminate\Support\Str;

class UpdateExistingExamSlugs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem cột slug đã tồn tại trong bảng baithi chưa
        if (Schema::hasColumn('baithi', 'slug')) {
            // Kiểm tra xem có bài thi nào chưa có slug không
            $examsWithoutSlug = BaiThi::whereNull('slug')->get();
            
            if ($examsWithoutSlug->count() > 0) {
                foreach ($examsWithoutSlug as $exam) {
                    $exam->slug = Str::slug($exam->ten_bai_thi);
                    $exam->save();
                }
            }
        }

        return $next($request);
    }
}