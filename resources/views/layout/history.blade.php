@extends('layout.main') {{-- Hoặc layout bạn đang dùng --}}
@section('title','History')
@section('content')
<div class="max-w-6xl mx-auto py-10 px-4">
    
    <div class="flex justify-between items-end mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-custom-dark">Lịch sử ôn tập</h1>
            <p class="text-gray-500 mt-1">Danh sách các bài bạn đã thực hiện</p>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        
        {{-- 1. THANH TÌM KIẾM --}}
        <form action="{{ route('history') }}" method="GET" class="w-full md:w-1/2 relative">
            <input type="text" name="keyword" 
                   value="{{ request('keyword') }}" 
                   placeholder="Tìm kiếm theo tên bài test..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:border-custom-main shadow-sm">
            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            
            @if(request('keyword'))
                <a href="{{ route('history') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500" title="Xóa tìm kiếm">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </form>

        {{-- 2. NÚT XÓA TẤT CẢ LỊCH SỬ --}}
        {{-- Chỉ hiện nút này nếu có lịch sử --}}
        @if(isset($histories) && $histories->count() > 0)
            <form action="{{ route('clearHistory') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa TOÀN BỘ lịch sử không? Hành động này không thể hoàn tác!');">
                @csrf
                @method('DELETE')
                <button type="submit" class="flex items-center space-x-2 px-5 py-2 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition font-semibold">
                    <i class="fas fa-trash-alt"></i>
                    <span>Xóa tất cả lịch sử</span>
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-4 px-6 font-bold">Thời gian</th>
                        <th class="py-4 px-6 font-bold">Bài Test (ID)</th>
                        <th class="py-4 px-6 font-bold text-center">Kết quả</th>
                        <th class="py-4 px-6 font-bold text-center">Trạng thái</th>
                        <th class="py-4 px-6 font-bold text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    @forelse($histories as $item)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition duration-150">
                            
                            {{-- 1. Thời gian --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 text-blue-600 rounded-full p-2 mr-3">
                                        <i class="far fa-clock"></i>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-gray-800">
                                            {{ \Carbon\Carbon::parse($item->done_at)->format('H:i - d/m/Y') }}
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($item->done_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Tên bài & ID --}}
                            <td class="py-4 px-6">
                                <div class="font-bold text-base text-custom-dark mb-1">
                                    {{ $item->test_title }}
                                </div>
                                <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-full text-xs">
                                    ID: #{{ $item->testID }}
                                </span>
                            </td>

                            {{-- 3. Điểm số / Tổng câu --}}
                            <td class="py-4 px-6 text-center">
                                <span class="text-lg font-bold text-gray-800">
                                    {{ $item->correct_question }} / {{ $item->total_questions }}
                                </span>
                                <span class="text-xs text-gray-400 block">câu đúng</span>
                            </td>

                            {{-- 4. Đánh giá (Màu sắc) --}}
                            <td class="py-4 px-6 text-center">
                                @php
                                    // Tính phần trăm điểm
                                    $percent = ($item->total_questions > 0) 
                                        ? round(($item->correct_question / $item->total_questions) * 100) 
                                        : 0;
                                    
                                    // Chọn màu
                                    if($percent >= 80) {
                                        $badgeClass = 'bg-green-100 text-green-700';
                                        $label = 'Xuất sắc';
                                    } elseif($percent >= 50) {
                                        $badgeClass = 'bg-blue-100 text-blue-700';
                                        $label = 'Đạt';
                                    } else {
                                        $badgeClass = 'bg-red-100 text-red-700';
                                        $label = 'Cần cố gắng';
                                    }
                                @endphp
                                <span class="{{ $badgeClass }} py-1 px-3 rounded-full text-xs font-bold uppercase">
                                    {{ $percent }}% - {{ $label }}
                                </span>
                            </td>

                            {{-- 5. Nút Làm lại --}}
                            <td class="py-4 px-6 text-center">
                                {{-- Link này trỏ vào route retryTest mà chúng ta đã làm ở bước trước --}}
                                <a href="{{ route('joinTest', ['id' => $item->testID]) }}" 
                                   class="transform hover:scale-110 block text-custom-main hover:text-custom-dark transition"
                                   title="Làm lại bài này">
                                    <i class="fas fa-redo-alt text-xl"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        {{-- Nếu chưa có lịch sử nào --}}
                        <tr>
                            <td colspan="5" class="py-10 text-center text-gray-500">
                                <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" alt="Empty" class="w-40 mx-auto mb-4 opacity-50">
                                <p class="text-lg">Bạn chưa làm bài kiểm tra nào.</p>
                                <a href="{{ route('create') }}" class="text-custom-main font-bold mt-2 inline-block">Bắt đầu học ngay</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>{{ $histories->links('vendor.pagination.bootstrap-5') }}</div>
        </div>
    </div>
</div>
@endsection