<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use App\Models\KetQuaBaiThi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:quan_tri');
    }
    
    public function index()
    {
        $users = NguoiDung::all();
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|unique:NguoiDung',
            'mat_khau' => 'required|min:6',
            'ho_ten' => 'required',
            'email' => 'required|email|unique:NguoiDung',
            'vai_tro' => 'required|in:quan_tri,giao_vien,hoc_sinh',
        ]);
        
        NguoiDung::create([
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'mat_khau' => Hash::make($request->mat_khau),
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'vai_tro' => $request->vai_tro,
        ]);
        
        return redirect()->route('admin.users.index')->with('success', 'Tạo người dùng thành công!');
    }
    
    public function edit($id)
    {
        $user = NguoiDung::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }
    
    public function update(Request $request, $id)
    {
        $user = NguoiDung::findOrFail($id);
        
        $rules = [
            'ho_ten' => 'required',
            'email' => 'required|email|unique:NguoiDung,email,'.$id.',ma_nguoi_dung',
            'vai_tro' => 'required|in:quan_tri,giao_vien,hoc_sinh',
        ];
        
        if ($request->filled('mat_khau')) {
            $rules['mat_khau'] = 'min:6';
        }
        
        $request->validate($rules);
        
        $data = [
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'vai_tro' => $request->vai_tro,
        ];
        
        if ($request->filled('mat_khau')) {
            $data['mat_khau'] = Hash::make($request->mat_khau);
        }
        
        $user->update($data);
        
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }
    
    public function destroy($id)
    {
        $user = NguoiDung::findOrFail($id);
        
        // Không cho phép xóa chính mình
        if ($user->ma_nguoi_dung === auth()->user()->ma_nguoi_dung) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa tài khoản của chính bạn!');
        }
        
        // Kiểm tra nếu là học sinh và đã làm bài kiểm tra
        if ($user->vai_tro == 'hoc_sinh') {
            $hasResults = KetQuaBaiThi::where('ma_nguoi_dung', $id)->exists();
            if ($hasResults) {
                return redirect()->route('admin.users.index')->with('error', 'Không thể xóa học sinh này vì đã có kết quả bài thi!');
            }
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công!');
    }
}