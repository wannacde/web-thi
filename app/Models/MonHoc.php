<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonHoc extends Model
{
    use HasFactory;

    protected $table = 'MonHoc';
    protected $primaryKey = 'ma_mon_hoc';
    public $timestamps = false;

    protected $fillable = [
        'ten_mon_hoc',
        'mo_ta',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($monHoc) {
            $monHoc->slug = \Illuminate\Support\Str::slug($monHoc->ten_mon_hoc);
        });
        static::updating(function ($monHoc) {
            $monHoc->slug = \Illuminate\Support\Str::slug($monHoc->ten_mon_hoc);
        });
    }

    public function chuong()
    {
        return $this->hasMany(Chuong::class, 'ma_mon_hoc');
    }

    public function baiThi()
    {
        return $this->hasMany(BaiThi::class, 'ma_mon_hoc');
    }
}