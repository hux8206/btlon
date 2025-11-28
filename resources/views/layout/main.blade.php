<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabulary Learning Dashboard - Modern Style</title>
    <!-- Tải Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Tải Font Awesome cho Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Tải font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Cấu hình Tailwind cho màu sắc xanh chủ đạo -->
    <link rel="stylesheet" href="{{ asset('home.css') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'custom-main': '#00c7e0', // Tông xanh chủ đạo
                        'custom-dark': '#004c66', // Tông xanh đậm
                        'custom-light-bg': '#f0f8ff', // Nền xanh nhạt
                        'custom-text': '#333333',
                    }
                }
            }
        }
    </script>
</head>

<body>
    <header>
        <nav class="bg-white shadow-md sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">

                    <!-- Logo và Tên Ứng dụng -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="#" class="text-2xl font-extrabold text-custom-dark flex items-center">
                            <i class="fas fa-book-open mr-2 text-custom-main"></i>
                            VOCABIZ
                        </a>
                    </div>

                    <!--user name-->
                    @auth
                    <div>
                        <span class="text-black">{{ auth()->user()->fullName }}</span>
                        <a href="{{ route('logout') }}" class="text-black">
                            Logout
                        </a>
                    </div>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- 2. KHU VỰC CHÍNH (MAIN CONTENT) -->
    @yield('content')

    <!-- 7. FOOTER -->
    <footer class="mt-20 bg-white border-t border-gray-200 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8">

                <!-- Cột 1: Logo và Thông tin chung -->
                <div class="col-span-2 md:col-span-1">
                    <a href="#" class="text-2xl font-extrabold text-custom-dark flex items-center mb-4">
                        <i class="fas fa-book-open mr-2 text-custom-main"></i>
                        VOCABIZ
                    </a>
                    <p class="text-sm text-gray-500">
                        Nền tảng học từ vựng tương tác theo phong cách Quizizz.
                    </p>
                    <div class="flex items-center mt-4 text-custom-dark text-sm space-x-2">
                        <i class="fas fa-universal-access"></i>
                        <span>Tính năng Trợ năng</span>
                    </div>
                </div>

                <!-- Cột 2: Tài nguyên (Resources) -->
                <div>
                    <h5 class="text-lg font-semibold text-custom-dark mb-4">Tài nguyên</h5>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-custom-main transition">Kho bài học</a></li>
                        <li><a href="#" class="hover:text-custom-main transition">Tạo bài Quiz</a></li>
                        <li><a href="#" class="hover:text-custom-main transition">Kế hoạch học tập</a></li>
                        <li><a href="#" class="hover:text-custom-main transition">Hướng dẫn sử dụng</a></li>
                    </ul>
                </div>

                <!-- Cột 3: Công ty (Company) -->
                <div>
                    <h5 class="text-lg font-semibold text-custom-dark mb-4">Công ty</h5>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-custom-main transition">Về chúng tôi</a></li>
                        <li><a href="#" class="hover:text-custom-main transition">Tuyển dụng</a></li>
                        <li><a href="#" class="hover:text-custom-main transition">Chính sách bảo mật</a></li>
                        <li><a href="#" class="hover:text-custom-main transition">Điều khoản dịch vụ</a></li>
                    </ul>
                </div>

                <!-- Cột 4: Hỗ trợ (Support) -->
                <div>
                    <h5 class="text-lg font-semibold text-custom-dark mb-4">Hỗ trợ</h5>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-custom-main transition">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="hover:text-custom-main transition">Liên hệ Hỗ trợ</a></li>
                        <li><a href="#" class="hover:text-custom-main transition">Báo cáo lỗi</a></li>
                    </ul>
                </div>

                <!-- Cột 5: Tải ứng dụng (Mobile) -->
                <div class="col-span-2 md:col-span-1 md:text-right">
                    <h5 class="text-lg font-semibold text-custom-dark mb-4">Tải ứng dụng</h5>
                    <a href="#" class="block mb-3">
                        <img src="https://placehold.co/150x50/34495e/ffffff?text=Google+Play" alt="Google Play" class="inline-block rounded-lg shadow-md hover:opacity-90 transition">
                    </a>
                    <a href="#" class="block">
                        <img src="https://placehold.co/150x50/34495e/ffffff?text=App+Store" alt="App Store" class="inline-block rounded-lg shadow-md hover:opacity-90 transition">
                    </a>
                </div>

            </div>

            <!-- Dải bản quyền và Mạng xã hội -->
            <div class="mt-10 pt-6 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <span>© 2025 Vocabiz, Inc. Mọi quyền được bảo lưu.</span>
                <div class="flex space-x-4 mt-4 md:mt-0 text-xl">
                    <a href="#" class="hover:text-custom-main transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-custom-main transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-custom-main transition"><i class="fab fa-pinterest"></i></a>
                    <a href="#" class="hover:text-custom-main transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>