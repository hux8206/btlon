@extends('layout.main')

@section('title')

@section('content')

<main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <!-- Tiêu đề, Robot và Khu vực Tìm kiếm -->
    <div class="mb-12 p-8 rounded-2xl header-bg shadow-xl flex flex-col md:flex-row items-center justify-between text-white">

        <!-- Văn bản và Tiêu đề -->
        <div class="md:w-3/5 text-left md:text-left mb-6 md:mb-0">
            <h1 class="text-4xl md:text-5xl font-extrabold leading-snug">
                <span class="text-white">Chào mừng!</span> Hãy cùng Robot học Từ Vựng!
            </h1>
            <p class="mt-3 text-xl opacity-90">
                Khám phá các bộ từ vựng Tiếng Anh được thiết kế theo phong cách Quizizz.
            </p>

            <!-- Thanh Tìm kiếm Lớn (đặt lại vào đây để tiện) -->
            <div class="mt-6 max-w-xl relative">
                <input
                    type="text"
                    placeholder="Tìm kiếm chủ đề, từ vựng hoặc mã bài học..."
                    class="w-full py-3 pl-12 pr-4 text-gray-700 border border-gray-300 rounded-full focus:border-custom-dark focus:ring-custom-dark focus:ring-1 focus:outline-none transition shadow-inner">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>

        <!-- Hình Robot vẫy tay (SVG) -->
        <div class="md:w-2/5 flex justify-center">
            <svg class="w-48 h-48 md:w-64 md:h-64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Body -->
                <rect x="7" y="10" width="10" height="9" rx="2" fill="#FFFFFF" />
                <!-- Head -->
                <rect x="8" y="4" width="8" height="6" rx="1" fill="#FFFFFF" />
                <!-- Eye 1 -->
                <circle cx="10" cy="7" r="1" fill="#000000" />
                <!-- Eye 2 -->
                <circle cx="14" cy="7" r="1" fill="#000000" />
                <!-- Antenna -->
                <path d="M12 4L12 2" stroke="#FFFFFF" stroke-width="2" stroke-linecap="round" />
                <!-- Hand 1 (Fixed) -->
                <rect x="5" y="11" width="2" height="6" rx="1" fill="#FFFFFF" />
                <!-- Hand 2 (Waving Arm) -->
                <g class="waving-robot">
                    <rect x="17" y="11" width="2" height="6" rx="1" fill="#FFFFFF" />
                    <rect x="19" y="14" width="2" height="2" rx="1" fill="#FFFFFF" />
                </g>
                <!-- Feet -->
                <rect x="8" y="19" width="3" height="2" rx="1" fill="#AAAAAA" />
                <rect x="13" y="19" width="3" height="2" rx="1" fill="#AAAAAA" />
            </svg>
        </div>

    </div>

    <!-- 4. DANH MỤC TỪ VỰNG PHỔ BIẾN -->
    <div class="mb-10">
        <h2 class="text-3xl font-bold text-custom-dark mb-6 text-center md:text-left">Danh mục Phổ biến</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Category Card 1: Công Sở -->
            <div class="bg-white p-4 rounded-xl card-shadow hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 cursor-pointer flex flex-col items-center justify-center space-y-2">
                <i class="fas fa-chart-line text-3xl text-yellow-600"></i>
                <a class="font-semibold text-gray-800 text-center" href="#">Thống kê</a>
            </div>
            <!-- Category Card 2: IELTS -->
            <div class="bg-white p-4 rounded-xl card-shadow hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 cursor-pointer flex flex-col items-center justify-center space-y-2">
                <i class="fas fa-clock-rotate-left text-3xl text-blue-600"></i>
                <span class="font-semibold text-gray-800 text-center">Lịch sử</span>
            </div>
            <!-- Category Card 3: Du Lịch -->
            <div class="bg-white p-4 rounded-xl card-shadow hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 cursor-pointer flex flex-col items-center justify-center space-y-2">
                <i class="fas fa-globe-americas text-3xl text-green-600"></i>
                <span class="font-semibold text-gray-800 text-center">Khám phá</span>
            </div>
            <!-- Category Card 4: Ẩm Thực -->
            <div class="bg-white p-4 rounded-xl card-shadow hover:shadow-xl transition duration-300 transform hover:-translate-y-0.5 cursor-pointer flex flex-col items-center justify-center space-y-2">
                <i class="fas fa-heart text-3xl text-red-600"></i>
                <span class="font-semibold text-gray-800 text-center">Yêu thích</span>
            </div>
        </div>
    </div>

    <!-- 5. CÁC BÀI HỌC NỔI BẬT -->
    <h2 class="text-3xl font-bold text-custom-dark mb-6 text-center md:text-left">Các Bộ Từ Vựng Nổi bật</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- Topic Card 1: 100 Từ Vựng IELTS -->
        <div class="bg-white rounded-xl overflow-hidden card-shadow hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
            <div class="p-6">
                <span class="inline-block bg-pink-100 text-pink-700 text-xs px-3 py-1 rounded-full font-semibold mb-3">IELTS</span>
                <h3 class="text-xl font-bold text-custom-dark mb-2">100 Từ Vựng "Ăn Điểm" cho IELTS Writing</h3>
                <p class="text-gray-600 text-sm mb-4">Hệ thống từ Academic hiệu quả để nâng band điểm 7.0+.</p>
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-list-alt text-custom-main"></i>
                        <span>50 Câu hỏi</span>
                    </span>
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-user-graduate text-custom-main"></i>
                        <span>1.2K Lượt chơi</span>
                    </span>
                </div>
                <button class="mt-4 w-full py-2 bg-custom-main text-white rounded-lg font-semibold hover:bg-custom-dark transition">
                    Bắt đầu Chơi
                </button>
            </div>
        </div>

        <!-- Topic Card 2: Từ Vựng Giao tiếp Hàng ngày -->
        <div class="bg-white rounded-xl overflow-hidden card-shadow hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
            <div class="p-6">
                <span class="inline-block bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-semibold mb-3">Giao tiếp</span>
                <h3 class="text-xl font-bold text-custom-dark mb-2">Từ Vựng Tiếng Anh Giao Tiếp Cơ Bản</h3>
                <p class="text-gray-600 text-sm mb-4">Các cụm từ cần thiết cho cuộc sống và công việc hàng ngày.</p>
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-list-alt text-custom-main"></i>
                        <span>40 Câu hỏi</span>
                    </span>
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-user-graduate text-custom-main"></i>
                        <span>3.5K Lượt chơi</span>
                    </span>
                </div>
                <button class="mt-4 w-full py-2 bg-custom-main text-white rounded-lg font-semibold hover:bg-custom-dark transition">
                    Bắt đầu Chơi
                </button>
            </div>
        </div>

        <!-- Topic Card 3: Idioms Phổ biến -->
        <div class="bg-white rounded-xl overflow-hidden card-shadow hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
            <div class="p-6">
                <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-semibold mb-3">Thành ngữ</span>
                <h3 class="text-xl font-bold text-custom-dark mb-2">Các Idioms "Tây" Nhất để Nói Tự Nhiên</h3>
                <p class="text-gray-600 text-sm mb-4">Giúp bạn nghe và nói như người bản xứ ngay lập tức.</p>
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-list-alt text-custom-main"></i>
                        <span>30 Câu hỏi</span>
                    </span>
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-user-graduate text-custom-main"></i>
                        <span>900 Lượt chơi</span>
                    </span>
                </div>
                <button class="mt-4 w-full py-2 bg-custom-main text-white rounded-lg font-semibold hover:bg-custom-dark transition">
                    Bắt đầu Chơi
                </button>
            </div>
        </div>

    </div>

</main>

<!-- 6. KHU VỰC KÊU GỌI HÀNH ĐỘNG (CTA) -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 mb-20">
    <div class="cta-bg p-10 rounded-2xl card-shadow">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold text-custom-dark leading-tight mb-4">
                Tạo bài học cho riêng bạn!
            </h2>
            <p class="text-lg text-gray-600 mb-8">
                Tạo bài học và điều chỉnh nó theo nhu cầu của bạn chỉ trong vài phút.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                <!-- Nút Đăng ký (Primary CTA) -->
                <a href="{{ route('create') }}" class="px-8 py-3 text-lg font-semibold rounded-full text-white bg-custom-dark hover:bg-custom-main focus-ring-cyan transition shadow-lg">
                    Tạo bài dò
                </a>
                <!-- Nút Tìm hiểu thêm (Secondary CTA) 
                    <a href="#" class="px-8 py-3 text-lg font-semibold rounded-full text-custom-dark bg-white border-2 border-custom-dark hover:border-custom-main hover:text-custom-main focus-ring-cyan transition">
                        Người quản lý: Tìm hiểu thêm
                    </a>-->
            </div>
        </div>
    </div>
</section>

<!-- 3. KHU VỰC CÁC TÍNH NĂNG (FEATURE TILES) -->
<div class="mb-12 py-8">
    <div class="max-w-4xl mx-auto">
        <h3 class="text-2xl font-extrabold text-custom-dark mb-6 text-center">Giáo viên và Chuyên gia Công nghệ Yêu thích VOCABIZ cho...</h3>
        <div class="flex flex-wrap justify-center gap-3 md:gap-4">
            <!-- Hàng 1 -->
            <div class="feature-tile bg-blue-100 text-blue-700">
                <i class="fas fa-chalkboard-teacher mr-2"></i>Hướng dẫn Trực tiếp
            </div>
            <div class="feature-tile bg-red-100 text-red-700">
                <i class="fas fa-home mr-2"></i>Bài tập về Nhà
            </div>
            <div class="feature-tile bg-pink-100 text-pink-700">
                <i class="fas fa-clipboard-list mr-2"></i>Bài tập
            </div>
            <div class="feature-tile bg-purple-100 text-purple-700">
                <i class="fas fa-user mr-2"></i>Luyện tập Cá nhân
            </div>
            <div class="feature-tile bg-green-100 text-green-700">
                <i class="fas fa-clipboard-check mr-2"></i>Chuẩn bị Kiểm tra
            </div>
            <!-- Hàng 2 -->
            <div class="feature-tile bg-yellow-100 text-yellow-700">
                <i class="fas fa-file-powerpoint mr-2"></i>Bài thuyết trình
            </div>
            <div class="feature-tile bg-cyan-100 text-cyan-700">
                <i class="fas fa-chart-line mr-2"></i>Đánh giá Tổng kết
            </div>
            <div class="feature-tile bg-indigo-100 text-indigo-700">
                <i class="fas fa-check-double mr-2"></i>Đánh giá Định kỳ
            </div>
            <div class="feature-tile bg-orange-100 text-orange-700">
                <i class="fas fa-users mr-2"></i>Luyện tập Nhóm
            </div>
            <!-- Hàng 3 -->
            <div class="feature-tile bg-fuchsia-100 text-fuchsia-700">
                <i class="fas fa-puzzle-piece mr-2"></i>Trò chơi hóa (Gamification)
            </div>
            <div class="feature-tile bg-lime-100 text-lime-700">
                <i class="fas fa-wheelchair-move mr-2"></i>Điều chỉnh Trợ năng
            </div>
            <div class="feature-tile bg-teal-100 text-teal-700">
                <i class="fas fa-bell mr-2"></i>Thông báo
            </div>
        </div>
    </div>
</div>
@endsection