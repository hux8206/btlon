@extends('layout.main')
@section('title','Explore')
@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">

    <div class="mb-10">
        <h1 class="text-4xl font-extrabold text-custom-dark mb-2">Khám phá Bài học</h1>
        <p class="text-gray-500 mb-8">Tìm kiếm các bộ từ vựng công khai hoặc nhập mã để vào bài riêng tư.</p>

        <div class="flex flex-col md:flex-row justify-between gap-6 bg-white p-6 rounded-2xl shadow-md border border-gray-100">

            {{-- A. TÌM KIẾM BÀI PUBLIC --}}
            <form action="{{ route('explore') }}" method="GET" class="w-full md:w-1/2 relative">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tìm bài công khai</label>
                <div class="relative">
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="Nhập tên chủ đề, từ vựng..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-custom-main focus:border-custom-main transition">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </form>

            {{-- B. NHẬP ID BÀI RIÊNG TƯ --}}
            <form action="{{ route('private') }}" method="POST" class="w-full md:w-1/3">
                @csrf
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nhập mã bài (ID)</label>
                <div class="flex">
                    <input type="number" name="private_id" required
                        placeholder="VD: 105"
                        class="w-full px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-custom-main focus:border-custom-main transition">
                    <button type="submit" class="bg-custom-dark text-white px-6 py-3 rounded-r-lg font-bold hover:bg-custom-main transition">
                        Vào
                    </button>
                </div>
            </form>
        </div>

        {{-- Thông báo lỗi nếu nhập ID sai --}}
        @if(session('error'))
        <div class="mt-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-200 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($tests as $index => $test)
        @php
        // Logic đổi màu ngẫu nhiên cho đẹp
        $colors = [
        ['bg' => 'bg-pink-100', 'text' => 'text-pink-700', 'tag' => 'Public'],
        ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'tag' => 'Mới'],
        ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'tag' => 'Phổ biến'],
        ];
        $theme = $colors[$index % 3];
        @endphp

        <div class="bg-white rounded-xl overflow-hidden card-shadow hover:shadow-xl transition duration-300 transform hover:-translate-y-1 flex flex-col h-full border border-gray-100">
            <div class="p-6 flex-grow flex flex-col">

                {{-- Header Card: Tag màu & ID Bài Test --}}
                <div class="flex justify-between items-start mb-3">
                    <span class="inline-block {{ $theme['bg'] }} {{ $theme['text'] }} text-xs px-3 py-1 rounded-full font-semibold">
                        {{ $theme['tag'] }}
                    </span>

                    {{-- HIỂN THỊ ID BÀI TEST --}}
                    <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">
                        #{{ $test->testID }}
                    </span>
                </div>

                {{-- Tiêu đề bài Test --}}
                <h3 class="text-xl font-bold text-custom-dark mb-2 line-clamp-2" title="{{ $test->title }}">
                    {{ $test->title }}
                </h3>

                <div class="flex items-center mb-3">
                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mr-2">
                        <i class="fas fa-user text-xs"></i>
                    </div>
                    <p class="text-xs text-gray-500 font-medium">
                        <span class="text-custom-dark font-bold">{{ $test->author_name }}</span>
                    </p>
                </div>

                <p class="text-gray-600 text-sm mb-4">
                    Ngày tạo: {{ \Carbon\Carbon::parse($test->dayCreated)->format('d/m/Y') }}
                </p>

                {{-- Thông số: Số câu & Lượt chơi --}}
                <div class="flex justify-between items-center text-sm text-gray-500 mt-auto pt-4 border-t border-gray-50">
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-list-alt text-custom-main"></i>
                        <span>{{ $test->quantity }} Câu</span>
                    </span>
                    <span class="flex items-center space-x-1">
                        <i class="fas fa-user-graduate text-custom-main"></i>
                        <span>{{ $test->play_count }} Lượt</span>
                    </span>
                </div>

                {{-- Nút Bắt đầu (Trỏ về joinTest) --}}
                <div class="mt-5">
                    <a href="{{ route('joinTest', ['id' => $test->testID]) }}"
                        class="block w-full py-2 bg-custom-main text-white text-center rounded-lg font-semibold hover:bg-custom-dark transition shadow-md">
                        Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-16">
            <div class="text-gray-300 text-6xl mb-4"><i class="fas fa-search"></i></div>
            <p class="text-gray-500 text-xl font-medium">Không tìm thấy bài học nào phù hợp.</p>
            <a href="{{ route('discover') }}" class="text-custom-main hover:underline mt-2 inline-block">Xem tất cả bài học</a>
        </div>
        @endforelse
    </div>

    <div class="mt-10">
        {{ $tests->withQueryString()->links() }}
        {{-- Lưu ý: Cần cấu hình Pagination của Laravel sang Tailwind hoặc dùng CSS mặc định --}}
    </div>

</div>
@endsection