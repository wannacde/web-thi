<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraLoiNguoiDung extends Model
{
    use HasFactory;

    protected $table = 'TraLoiNguoiDung';
    protected $primaryKey = null; // Không có khóa chính đơn
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ma_ket_qua',
        'ma_cau_hoi',
        'dap_an_chon',
        'dung_sai',
    ];

    protected $casts = [
        'dung_sai' => 'boolean',
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