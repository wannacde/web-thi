<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraLoiNguoiDung extends Model
{
    use HasFactory;

    protected $table = 'TraLoiNguoiDung';
    public $timestamps = false; // Không cần timestamps cho bảng này

    protected $fillable = [
        'ma_ket_qua',
        'ma_cau_hoi',
        'dap_an_chon',
        'dung_sai',
    ];

    public function ketQuaBaiThi()
    {
        return $this->belongsTo(KetQuaBaiThi::class, 'ma_ket_qua');
    }

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'ma_cau_hoi');
    }
}
