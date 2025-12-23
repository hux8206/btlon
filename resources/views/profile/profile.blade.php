@extends('layout.main');

@section('title','Profile')

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
                <p class="text-2xl font-bold">{{ $totalPlayed }}</p>
                <p class="text-sm opacity-80">Bài test đã làm</p>
            </div>

        </div>

        <!-- Nút Cài đặt/Chỉnh sửa (Đặt riêng cho desktop) -->
        <button class="hidden md:block absolute bottom-3 right-8 px-4 py-2 bg-white text-custom-dark font-semibold rounded-full shadow hover:bg-gray-100 transition ">
            <i class="fas fa-edit mr-2"></i>Chỉnh sửa Hồ sơ
        </button>
    </div>

    <!-- KHU VỰC TABS & NỘI DUNG CHI TIẾT -->
    <div class="bg-white rounded-xl shadow-xl p-6">

        <!-- Tabs Navigation -->
        <div class="flex border-b border-gray-200 space-x-8 mb-6">

            <button onclick="switchTab('played')"
                id="tab-played"
                class="pb-3 text-lg font-semibold text-custom-main border-b-2 border-custom-main transition focus:outline-none">
                <i class="fas fa-graduation-cap mr-2"></i>Bài đã Chơi
            </button>

            <button onclick="switchTab('created')"
                id="tab-created"
                class="pb-3 text-lg font-semibold text-gray-500 hover:text-custom-main transition focus:outline-none">
                <i class="fas fa-plus-circle mr-2"></i>Bài đã Tạo
            </button>

            <button onclick="switchTab('settings')" id="tab-settings"
                class="pb-3 text-lg font-semibold text-gray-500 hover:text-custom-main transition whitespace-nowrap focus:outline-none">
                <i class="fas fa-cog mr-2"></i>Cài đặt Tài khoản
            </button>
        </div>
        <!-- Tab Content: Bài đã Chơi (Mặc định) -->
        <div id="played-quizzes" class="space-y-4">
            <h3 class="text-xl font-bold text-custom-dark mb-4">Lịch sử làm bài gần đây</h3>

            @forelse($playedTests as $history)
            @php
            // Tính phần trăm điểm
            $total = $history->question_completed;
            $score = $history->correct_question;
            $percent = ($total > 0) ? round(($score / $total) * 100) : 0;

            // Chọn màu dựa trên điểm số
            if($percent >= 80) {
            $colorClass = 'text-green-600 bg-green-50 border-green-200';
            } elseif ($percent >= 50) {
            $colorClass = 'text-yellow-600 bg-yellow-50 border-yellow-200';
            } else {
            $colorClass = 'text-red-600 bg-red-50 border-red-200';
            }
            @endphp

            <div class="flex flex-col md:flex-row justify-between items-center p-4 border border-gray-100 rounded-xl hover:shadow-md transition bg-white group">

                {{-- Phần thông tin bài thi --}}
                <div class="flex items-center w-full md:w-auto mb-4 md:mb-0">
                    {{-- Icon trang trí --}}
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center mr-4 text-custom-main group-hover:bg-custom-main group-hover:text-white transition">
                        <i class="fas fa-history"></i>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-800 text-lg group-hover:text-custom-main transition">
                            {{ $history->title }}
                        </h4>
                        <p class="text-sm text-gray-500">
                            <i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($history->done_at)->format('d/m/Y H:i') }}
                            <span class="mx-2">•</span>
                            <span>{{ $history->quantity }} câu hỏi</span>
                        </p>
                    </div>
                </div>

                {{-- Phần Điểm số & Nút bấm --}}
                <div class="flex items-center justify-between w-full md:w-auto gap-6">

                    {{-- Hiển thị điểm số --}}
                    <div class="text-center">
                        <span class="block text-xs text-gray-400 font-bold uppercase">Kết quả</span>
                        <div class="px-3 py-1 rounded-lg border {{ $colorClass }} font-bold text-sm">
                            {{ $score }}/{{ $total }} ({{ $percent }}%)
                        </div>
                    </div>

                    {{-- Nút Chơi lại --}}
                    <a href="{{ route('joinTest', ['id' => $history->testID]) }}"
                        class="px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-custom-main hover:text-white transition shadow-sm text-sm">
                        <i class="fas fa-redo-alt mr-1"></i> Chơi lại
                    </a>
                </div>
            </div>

            @empty
            {{-- Giao diện khi chưa chơi bài nào --}}
            <div class="text-center py-12 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl">
                <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-gamepad"></i></div>
                <p class="text-gray-500 font-medium">Bạn chưa chơi bài Quiz nào cả.</p>
                <a href="{{ route('discover') }}" class="inline-block mt-4 px-6 py-2 bg-custom-main text-white font-bold rounded-full hover:bg-custom-dark transition">
                    Khám phá ngay
                </a>
            </div>
            @endforelse
        </div>

        <div id="created-quizzes" class="space-y-4 hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-custom-dark">Bài bạn tạo gần đây</h3>
                <a href="{{ route('myTests') }}" class="text-custom-main hover:underline font-semibold">
                    Xem tất cả & Quản lý <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            @forelse($createdTests as $test)
            <div class="flex justify-between items-center p-4 border rounded-lg hover:bg-custom-light-bg transition">
                <div>
                    <p class="font-semibold text-gray-800">{{ $test->title }}</p>
                    <p class="text-sm text-gray-500">Tạo ngày: {{ \Carbon\Carbon::parse($test->dayCreated)->format('d/m/Y') }} • {{ $test->quantity }} câu</p>
                </div>
                <div>
                    {{-- Nút Quản lý --}}
                    <a href="{{ route('manageTest', ['id' => $test->testID]) }}" class="px-4 py-2 bg-gray-100 text-custom-dark font-bold rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-cog mr-1"></i> Quản lý
                    </a>
                </div>
            </div>
            @empty
            <p class="text-gray-500 italic">Bạn chưa tạo bài test nào.</p>
            @endforelse
        </div>

        <div id="settings-quizzes" class="hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- FORM 1: THÔNG TIN CÁ NHÂN --}}
                <div>
                    <h3 class="text-xl font-bold text-custom-dark mb-4 border-l-4 border-custom-main pl-3">Thông tin cá nhân</h3>
                    <form action="{{ route('updateInfo') }}" method="POST" class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Họ và tên</label>
                            <input type="text" name="fullName" value="{{ auth()->user()->fullName }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-custom-main focus:border-custom-main outline-none">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Địa chỉ Email</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-custom-main focus:border-custom-main outline-none">
                        </div>

                        <button type="submit" class="w-full py-2 bg-custom-main text-white font-bold rounded-lg hover:bg-custom-dark transition">
                            Lưu thông tin
                        </button>
                    </form>
                </div>

                {{-- FORM 2: ĐỔI MẬT KHẨU --}}
                <div>
                    <h3 class="text-xl font-bold text-custom-dark mb-4 border-l-4 border-red-500 pl-3">Đổi mật khẩu</h3>
                    @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('changePassword') }}" method="POST" class="bg-red-50 p-6 rounded-xl border border-red-100">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" required placeholder="********"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500 outline-none">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mật khẩu mới</label>
                            <input type="password" name="new_password" required placeholder="Ít nhất 6 ký tự"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500 outline-none">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" required placeholder="Nhập lại mật khẩu mới"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-red-500 focus:border-red-500 outline-none">
                        </div>

                        <button type="submit" class="w-full py-2 bg-red-500 text-white font-bold rounded-lg hover:bg-red-700 transition">
                            Đổi mật khẩu
                        </button>
                    </form>
                </div>

            </div>
        </div>



        {{-- SCRIPT CHUYỂN TAB --}}
        <script>
            function switchTab(tabName) {
                // 1. Ẩn hết nội dung
                document.getElementById('played-quizzes').classList.add('hidden');
                document.getElementById('created-quizzes').classList.add('hidden');
                document.getElementById('settings-quizzes').classList.add('hidden');
                // 2. Reset style của nút bấm (về màu xám)
                const tabs = ['played', 'created', 'settings'];
                tabs.forEach(t => {
                    const btn = document.getElementById('tab-' + t);
                    btn.classList.remove('text-custom-main', 'border-b-2', 'border-custom-main');
                    btn.classList.add('text-gray-500');
                });

                // 3. Hiện nội dung được chọn
                document.getElementById(tabName + '-quizzes').classList.remove('hidden');

                // 4. Highlight nút được chọn
                const activeBtn = document.getElementById('tab-' + tabName);
                activeBtn.classList.remove('text-gray-500');
                activeBtn.classList.add('text-custom-main', 'border-b-2', 'border-custom-main');
            }
            document.addEventListener("DOMContentLoaded", function() {
                // Mặc định không có tab nào được yêu cầu
                let activeTab = "";

                // Trường hợp 1: Controller yêu cầu (Khi update thành công)
                @if(session('active_tab'))
                activeTab = "{{ session('active_tab') }}";
                @endif

                // Trường hợp 2: Có lỗi nhập liệu (Validation Error)
                // Nếu có lỗi, Laravel tự động redirect về, ta cần mở lại tab Settings để hiện lỗi
                @if($errors -> any())
                activeTab = "settings";
                @endif

                // Nếu có yêu cầu thì chạy hàm switchTab ngay lập tức
                if (activeTab) {
                    switchTab(activeTab);
                }
            });
        </script>

    </div>

</main>

@endsection