<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetQuaBaiThi extends Model
{
    use HasFactory;

    protected $table = 'KetQuaBaiThi';
    protected $primaryKey = 'ma_ket_qua';
    public $timestamps = false; // Tắt timestamps

    protected $fillable = [
        'ma_bai_thi',
        'ma_nguoi_dung',
        'diem',
        'ngay_nop',
    ];

    protected $casts = [
        'ngay_nop' => 'datetime',
    ];

    public function baiThi()
    {
        return $this->belongsTo(BaiThi::class, 'ma_bai_thi');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung');
    }

    public function traLoi()
    {
        return $this->hasMany(TraLoiNguoiDung::class, 'ma_ket_qua');
    }
}
