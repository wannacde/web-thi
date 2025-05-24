<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sử dụng DB::statement thay vì Schema::table để tránh lỗi với doctrine/dbal
        DB::statement('ALTER TABLE DapAn MODIFY dung_sai TINYINT(1) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại kiểu dữ liệu ban đầu nếu cần
        DB::statement('ALTER TABLE DapAn MODIFY dung_sai CHAR(1) NOT NULL');
    }
};