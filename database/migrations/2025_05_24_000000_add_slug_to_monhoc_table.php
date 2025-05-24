<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\MonHoc;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('MonHoc', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('ten_mon_hoc');
        });

        // Cập nhật slug cho các môn học hiện có
        $subjects = MonHoc::all();
        foreach ($subjects as $subject) {
            $subject->slug = Str::slug($subject->ten_mon_hoc);
            $subject->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('MonHoc', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
