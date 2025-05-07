<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chuong extends Model
{
    use HasFactory;

    protected $table = 'Chuong';
    protected $primaryKey = 'ma_chuong';

    protected $fillable = [
        'ma_mon_hoc',
        'ten_chuong',
        'muc_do',
        'so_thu_tu',
        'mo_ta',
    ];

    public function cauHoi()
    {
        return $this->hasMany(CauHoi::class, 'ma_chuong');
    }

    public function monHoc()
    {
        return $this->belongsTo(MonHoc::class, 'ma_mon_hoc');
    }
}
