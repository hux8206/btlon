@extends('layout.main')
@section('title','Favourite')
@section('content')
<div class="max-w-5xl mx-auto py-12 px-4">

    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-custom-dark">Bộ sưu tập Yêu thích</h1>
            <p class="text-gray-500 mt-1">Các bài học bạn đã lưu lại để ôn tập.</p>
        </div>
        <div class="bg-pink-100 text-pink-600 px-4 py-2 rounded-full font-bold">
            <i class="fas fa-heart mr-2"></i> {{ count($favorites) }} bài
        </div>
    </div>

    <div class="space-y-6">
        @forelse($favorites as $test)
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300 border border-gray-100 flex flex-col md:flex-row group">
                
                {{-- Cột Trái: Hình ảnh trang trí hoặc Icon lớn --}}
                <div class="md:w-48 bg-gradient-to-br from-custom-main to-custom-dark flex items-center justify-center p-6 text-white">
                    <div class="text-center">
                        <i class="fas fa-book-open text-4xl mb-2 opacity-80 group-hover:scale-110 transition duration-300"></i>
                        <p class="text-xs font-semibold opacity-75">#{{ $test->testID }}</p>
                    </div>
                </div>

                {{-- Cột Phải: Nội dung --}}
                <div class="flex-grow p-6 flex flex-col justify-between">
                    
                    <div>
                        <div class="flex justify-between items-start">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-custom-main transition">
                                {{ $test->title }}
                            </h3>
                            
                            {{-- Nút Bỏ thích nhanh --}}
                            <form action="{{ route('favourite', ['id' => $test->testID]) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-gray-300 hover:text-red-500 transition" title="Bỏ yêu thích">
                                    <i class="fas fa-heart text-xl"></i>
                                </button>
                            </form>
                        </div>

                        <div class="flex items-center text-sm text-gray-500 mb-4">
                            <span class="mr-4"><i class="fas fa-user mr-1"></i> {{ $test->author_name }}</span>
                            <span class="mr-4"><i class="fas fa-list mr-1"></i> {{ $test->quantity }} câu hỏi</span>
                            <span><i class="far fa-clock mr-1"></i> Đã lưu: {{ \Carbon\Carbon::parse($test->favorited_at)->diffForHumans() }}</span>
                        </div>
                    </div>

                    {{-- Các nút hành động --}}
                    <div class="flex items-center space-x-4 mt-2">
                        <a href="{{ route('joinTest', ['id' => $test->testID]) }}" class="px-6 py-2 bg-custom-main text-white text-sm font-bold rounded-full hover:bg-custom-dark transition shadow-md">
                            <i class="fas fa-play mr-2"></i> Làm bài ngay
                        </a>
                        
                        {{-- Nếu muốn xem chi tiết (nếu có trang chi tiết) --}}
                        {{-- <a href="#" class="px-6 py-2 bg-gray-100 text-gray-600 text-sm font-bold rounded-full hover:bg-gray-200 transition">
                            Xem trước
                        </a> --}}
                    </div>
                </div>
            </div>

        @empty
            <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-dashed border-gray-300">
                <div class="text-gray-200 text-7xl mb-4"><i class="far fa-heart"></i></div>
                <h3 class="text-xl font-bold text-gray-400 mb-2">Chưa có bài yêu thích nào</h3>
                <p class="text-gray-400 mb-6">Hãy khám phá và thả tim các bài học bạn tâm đắc nhé!</p>
                <a href="{{ route('explore') }}" class="px-8 py-3 bg-custom-main text-white font-bold rounded-full hover:bg-custom-dark transition">
                    Khám phá ngay
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection