<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaiThi extends Model
{
    use HasFactory;

    protected $table = 'baithi';
    protected $primaryKey = 'ma_bai_thi';
    public $timestamps = false;

    protected $fillable = [
        'ma_mon_hoc',
        'nguoi_tao',
        'ten_bai_thi',
        'tong_so_cau',
        'thoi_gian',
        'ngay_tao',
        'slug'
    ];

    // Tự động tạo slug khi lưu bài thi
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($baiThi) {
            $baiThi->slug = Str::slug($baiThi->ten_bai_thi);
        });

        static::updating(function ($baiThi) {
            $baiThi->slug = Str::slug($baiThi->ten_bai_thi);
        });
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc', 'ma_mon_hoc');
    }

    public function cauHoi()
    {
        return $this->belongsToMany(CauHoi::class, 'baithi_cauhoi', 'ma_bai_thi', 'ma_cau_hoi');
    }

    public function ketQuaBaiThi()
    {
        return $this->hasMany(KetQuaBaiThi::class, 'ma_bai_thi', 'ma_bai_thi');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao', 'ma_nguoi_dung');
    }
}