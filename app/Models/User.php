<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * 1. KHAI BÁO KHÓA CHÍNH
     * Vì bạn dùng 'userID' thay vì 'id' mặc định của Laravel
     */
    protected $primaryKey = 'userID';
    public $timestamps = false;
    /**
     * 2. KHAI BÁO CÁC CỘT ĐƯỢC PHÉP NHẬP LIỆU (Mass Assignment)
     * Sửa lại theo tên cột trong database của bạn
     */
    protected $fillable = [
        'email',
        'pass',
        'fullName',
    ];

    /**
     * 3. KHAI BÁO CÁC CỘT CẦN ẨN
     * Khi xuất dữ liệu user ra API, cột này sẽ bị giấu đi
     */
    protected $hidden = [
        'pass',           // Sửa password thành pass
        'remember_token',
    ];

    /**
     * 4. CHỈ ĐỊNH CỘT MẬT KHẨU CHO LARAVEL
     * Mặc định Laravel tìm cột 'password'. 
     * Hàm này báo cho nó biết cột mật khẩu của bạn tên là 'pass'.
     */
    public function getAuthPassword()
    {
        return $this->pass;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // 'email_verified_at' => 'datetime', // Bạn không có cột email nên dòng này có thể bỏ
            'pass' => 'hashed', // Tự động mã hóa khi lưu và kiểm tra cột 'pass'
        ];
    }
}