<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'NguoiDung';
    protected $primaryKey = 'ma_nguoi_dung';
    public $timestamps = false; // Tắt timestamps

    protected $fillable = [
        'ten_dang_nhap',
        'mat_khau',
        'ho_ten',
        'email',
        'vai_tro',
    ];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    // Ghi đè phương thức để Laravel Auth hoạt động với tên cột tùy chỉnh
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    public function baiThi()
    {
        return $this->hasMany(BaiThi::class, 'nguoi_tao');
    }
    
    public function ketQuaBaiThi()
    {
        return $this->hasMany(KetQuaBaiThi::class, 'ma_nguoi_dung');
    }
}
