<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DapAn extends Model
{
    use HasFactory;

    protected $table = 'DapAn';
    protected $primaryKey = 'ma_dap_an';

    protected $fillable = [
        'ma_cau_hoi',
        'noi_dung',
        'dung_sai',
    ];

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'ma_cau_hoi');
    }
}
