<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiThiCauHoi extends Model
{
    use HasFactory;

    protected $table = 'BaiThi_CauHoi';
    public $timestamps = false; // Không cần timestamps cho bảng này

    protected $fillable = [
        'ma_bai_thi',
        'ma_cau_hoi',
    ];
}

