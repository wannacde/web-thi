<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\BaiThi;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('baithi', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('ten_bai_thi');
        });

        // Cập nhật slug cho các bài thi hiện có
        $exams = BaiThi::all();
        foreach ($exams as $exam) {
            $exam->slug = Str::slug($exam->ten_bai_thi);
            $exam->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('baithi', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};