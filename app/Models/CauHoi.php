<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHoi extends Model
{
    use HasFactory;

    protected $table = 'CauHoi';
    protected $primaryKey = 'ma_cau_hoi';
    public $timestamps = false; // Tắt timestamps

    protected $fillable = [
        'ma_chuong',
        'noi_dung',
        'loai_cau_hoi',
        'nguoi_tao',
    ];

    public function dapAn()
    {
        return $this->hasMany(DapAn::class, 'ma_cau_hoi');
    }

    public function chuong()
    {
        return $this->belongsTo(Chuong::class, 'ma_chuong');
    }
}
