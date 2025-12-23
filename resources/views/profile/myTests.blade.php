@extends('layout.main')
@section('content')

<div class="max-w-6xl mx-auto py-10 px-4">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-custom-dark">Thư viện của tôi</h1>
            <p class="text-gray-500">Quản lý tất cả các bài ôn tập bạn đã tạo.</p>
        </div>
        
        {{-- THANH TÌM KIẾM --}}
        <form action="{{ route('myTests') }}" method="GET" class="w-full md:w-1/3 relative">
            <input type="text" name="keyword" value="{{ request('keyword') }}" 
                   placeholder="Tìm bài theo tên..." 
                   class="w-full pl-10 pr-4 py-2 border rounded-full focus:ring-custom-main focus:border-custom-main">
            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
        </form>
    </div>

    {{-- DANH SÁCH BÀI TEST --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @forelse($myTests as $test)
        <div class="flex flex-col md:flex-row items-center justify-between p-6 border-b hover:bg-gray-50 transition last:border-0">
            
            {{-- Thông tin bài --}}
            <div class="flex-1 mb-4 md:mb-0">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-lg font-bold text-custom-dark">{{ $test->title }}</h3>
                    <span class="text-xs px-2 py-0.5 rounded {{ $test->mode == 0 ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ $test->mode == 0 ? 'Public' : 'Private' }}
                    </span>
                </div>
                <div class="text-sm text-gray-500 flex items-center gap-4">
                    <span><i class="far fa-calendar mr-1"></i> {{ \Carbon\Carbon::parse($test->dayCreated)->format('d/m/Y') }}</span>
                    <span><i class="fas fa-list mr-1"></i> {{ $test->quantity }} câu</span>
                    <span><i class="fas fa-users mr-1"></i> {{ $test->play_count }} lượt chơi</span>
                </div>
            </div>

            {{-- Các nút hành động --}}
            <div class="flex items-center gap-3">
                {{-- Nút Xem Chi Tiết (Manage) --}}
                <a href="{{ route('manageTest', ['id' => $test->testID]) }}" 
                   class="px-5 py-2 bg-blue-50 text-blue-600 font-bold rounded-lg hover:bg-blue-100 transition">
                    <i class="fas fa-cog mr-1"></i> Chi tiết
                </a>

                {{-- Nút Xóa --}}
                <form action="{{ route('deleteTest', ['id' => $test->testID]) }}" method="POST" 
                      onsubmit="return confirm('CẢNH BÁO: Xóa bài này sẽ mất vĩnh viễn toàn bộ từ vựng và lịch sử chơi của mọi người. Bạn chắc chắn muốn xóa?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-5 py-2 bg-red-50 text-red-600 font-bold rounded-lg hover:bg-red-100 transition" title="Xóa bài này">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-10 text-center">
            <p class="text-gray-500 mb-4">Bạn chưa tạo bài nào cả.</p>
            <a href="{{ route('create') }}" class="px-6 py-2 bg-custom-main text-white font-bold rounded-full">Tạo bài ngay</a>
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $myTests->links() }}
    </div>
</div>
@endsection