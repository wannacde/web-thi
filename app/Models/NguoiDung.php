<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    use HasFactory;

    protected $table = 'NguoiDung'; // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'ma_nguoi_dung'; // Khóa chính

    protected $fillable = [
        'ten_dang_nhap',
        'mat_khau',
        'ho_ten',
        'email',
        'vai_tro',
    ];

    // Định nghĩa quan hệ với bảng khác (nếu có)
    public function baiThi()
    {
        return $this->hasMany(BaiThi::class, 'nguoi_tao');
    }
}
