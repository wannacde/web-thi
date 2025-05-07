<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiThi extends Model
{
    use HasFactory;

    protected $table = 'BaiThi';
    protected $primaryKey = 'ma_bai_thi';

    protected $fillable = [
        'ma_mon_hoc',
        'ten_bai_thi',
        'tong_so_cau',
        'thoi_gian',
        'nguoi_tao',
    ];

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc');
    }

    public function cauHoi()
    {
        return $this->belongsToMany(CauHoi::class, 'BaiThi_CauHoi', 'ma_bai_thi', 'ma_cau_hoi');
    }
}
