@extends('layout.main');

@section('title')

@section('content')
     <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        
        <!-- THÔNG TIN TÓM TẮT & BANNER -->
        <div class="profile-banner-bg rounded-xl shadow-xl p-8 mb-8 relative flex flex-col md:flex-row md:items-start md:justify-between">
            
            <!-- Phần bên trái: Ảnh đại diện và Tên -->
            <div class="flex items-center space-x-6 md:w-3/5">
                <img class="w-24 h-24 rounded-full border-4 border-white object-cover" 
                     src="https://placehold.co/100x100/004c66/ffffff?text=VN" 
                     alt="Ảnh đại diện người dùng">
                <div>
                    <h1 class="text-3xl font-extrabold text-white">{{ auth()->user()->fullName }}</h1>
                    <p class="text-sm text-gray-300">{{ auth()->user()->email }}</p>
                    
                    <br>
                    <br>
                    <br>
                    <!-- Nút Cài đặt/Chỉnh sửa (Đặt ở đây cho mobile) -->
                    <button class="mt-4 px-4 py-2 bg-white text-custom-dark font-semibold rounded-full shadow hover:bg-gray-100 transition md:hidden">
                        <i class="fas fa-edit mr-2"></i>Chỉnh sửa Hồ sơ
                    </button>
                </div>
            </div>
            
            <!-- Thống kê Tóm tắt (Phần bên phải Banner) -->
            <div class="mt-6 md:mt-0 flex flex-wrap md:w-2/5 justify-start md:justify-end gap-4 text-white">
                <div class="text-center bg-white bg-opacity-10 p-3 rounded-lg w-full sm:w-1/3 md:w-auto">
                    <p class="text-2xl font-bold">128</p>
                    <p class="text-sm opacity-80">Bài Quiz Đã Chơi</p>
                </div>
                <div class="text-center bg-white bg-opacity-10 p-3 rounded-lg w-full sm:w-1/3 md:w-auto">
                    <p class="text-2xl font-bold">95%</p>
                    <p class="text-sm opacity-80">Độ Chính Xác TB</p>
                </div>
                <div class="text-center bg-white bg-opacity-10 p-3 rounded-lg w-full sm:w-1/3 md:w-auto">
                    <p class="text-2xl font-bold">8700</p>
                    <p class="text-sm opacity-80">Điểm Cao Nhất</p>
                </div>
            </div>
            
            <!-- Nút Cài đặt/Chỉnh sửa (Đặt riêng cho desktop) -->
            <button class="hidden md:block absolute bottom-8 right-8 px-4 py-2 bg-white text-custom-dark font-semibold rounded-full shadow hover:bg-gray-100 transition">
                <i class="fas fa-edit mr-2"></i>Chỉnh sửa Hồ sơ
            </button>
        </div>

        <!-- KHU VỰC TABS & NỘI DUNG CHI TIẾT -->
        <div class="bg-white rounded-xl shadow-xl p-6">
            
            <!-- Tabs Navigation -->
            <div class="flex border-b border-gray-200 space-x-8 mb-6">
                <button class="pb-3 text-lg font-semibold text-custom-main border-b-2 border-custom-main transition">
                    <i class="fas fa-graduation-cap mr-2"></i>Bài đã Chơi
                </button>
                <button class="pb-3 text-lg font-semibold text-gray-500 hover:text-custom-main transition">
                    <i class="fas fa-plus-circle mr-2"></i>Bài đã Tạo
                </button>
                <button class="pb-3 text-lg font-semibold text-gray-500 hover:text-custom-main transition">
                    <i class="fas fa-cog mr-2"></i>Cài đặt Tài khoản
                </button>
            </div>
            
            <!-- Tab Content: Bài đã Chơi (Mặc định) -->
            <div id="played-quizzes" class="space-y-4">
                <h3 class="text-xl font-bold text-custom-dark mb-4">Lịch sử Chơi Quiz gần đây</h3>
                
                <!-- History Item 1 -->
                <div class="flex justify-between items-center p-4 border rounded-lg hover:bg-custom-light-bg transition">
                    <div>
                        <p class="font-semibold text-gray-800">Từ vựng IELTS Chủ đề Môi trường</p>
                        <p class="text-sm text-gray-500">Hoàn thành: 2 ngày trước</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="font-bold text-lg text-green-600">92%</span>
                        <button class="text-custom-main hover:text-custom-dark transition">
                            <i class="fas fa-redo"></i> Chơi lại
                        </button>
                    </div>
                </div>
                
                <!-- History Item 2 -->
                <div class="flex justify-between items-center p-4 border rounded-lg hover:bg-custom-light-bg transition">
                    <div>
                        <p class="font-semibold text-gray-800">100 Idioms Giao tiếp (Cấp độ B2)</p>
                        <p class="text-sm text-gray-500">Hoàn thành: 1 tuần trước</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="font-bold text-lg text-yellow-600">75%</span>
                        <button class="text-custom-main hover:text-custom-dark transition">
                            <i class="fas fa-redo"></i> Chơi lại
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
        
    </main>

@endsection