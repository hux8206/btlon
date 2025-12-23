@extends('layout.main')

@section('title', 'Chỉnh sửa Từ vựng')

@section('content')

<div class="max-w-4xl mx-auto py-8 px-4">

    {{-- HEADER: Quay lại & Tiêu đề --}}
    <div class="flex items-center justify-between mb-8">
        <div>
           
            <h1 class="text-2xl font-bold text-custom-dark">
                Bộ từ vựng: <span class="text-custom-main">{{ $test->title }}</span>
            </h1>
            <p class="text-sm text-gray-500">Tổng cộng: <b>{{ count($vocabs) }}</b> từ</p>
        </div>
    </div>

    {{-- PHẦN 1: FORM THÊM TỪ MỚI (Nổi bật) --}}
    <div class="bg-white rounded-xl shadow-md p-6 mb-8 border-l-4 border-green-500">
        <h3 class="font-bold text-gray-700 mb-4 flex items-center">
            <i class="fas fa-plus-circle text-green-500 mr-2"></i> Thêm từ mới
        </h3>
        
        <form action="{{ route('addVocab', ['id' => $test->testID]) }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
            @csrf
            
            {{-- Ô nhập Từ vựng --}}
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Từ vựng / câu hỏi</label>
                <input type="text" name="question" placeholder="Ví dụ: Apple" required
                       class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-green-500 focus:outline-none transition font-medium">
            </div>

            {{-- Ô nhập Nghĩa --}}
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-500 mb-1 uppercase">Đáp án</label>
                <input type="text" name="meaning" placeholder="Ví dụ: Quả táo" required
                       class="w-full border-2 border-gray-200 rounded-lg p-3 focus:border-green-500 focus:outline-none transition font-medium">
            </div>

            {{-- Nút Thêm --}}
            <button type="submit" class="w-full md:w-auto px-6 py-3.5 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-lg flex items-center justify-center">
                <i class="fas fa-save mr-2"></i> Lưu
            </button>
        </form>
    </div>

    {{-- PHẦN 2: DANH SÁCH TỪ VỰNG (Sửa trực tiếp) --}}
    {{-- PHẦN 2: DANH SÁCH TỪ VỰNG (Đã cập nhật nút Lưu/Xóa) --}}
    <div class="space-y-4">
        @forelse($vocabs as $index => $v)
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 transition hover:shadow-md flex flex-col md:flex-row items-center gap-3">
                
                {{-- FORM CẬP NHẬT (Chiếm phần lớn diện tích) --}}
                <form action="{{ route('updateVocab', ['id' => $v->vocabID]) }}" method="POST" 
                      class="flex-1 w-full flex flex-col md:flex-row items-center gap-3">
                    @csrf
                    
                    {{-- Số thứ tự --}}
                    <div class="text-gray-400 font-bold w-6 text-center hidden md:block">
                        {{ count($vocabs) - $index }}
                    </div>

                    {{-- Input Question --}}
                    <div class="flex-1 w-full relative">
                        <span class="absolute top-2.5 left-3 text-xs text-gray-400 font-bold"></span>
                        <input type="text" name="question" value="{{ $v->question }}" required
                               class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 pl-8 focus:bg-white focus:border-custom-main focus:ring-1 focus:ring-custom-main outline-none transition font-semibold text-gray-800">
                    </div>

                    {{-- Mũi tên (Trang trí) --}}
                    <div class="text-gray-300 hidden md:block">
                        <i class="fas fa-arrow-right"></i>
                    </div>

                    {{-- Input Meaning --}}
                    <div class="flex-1 w-full relative">
                        <span class="absolute top-2.5 left-3 text-xs text-gray-400 font-bold"></span>
                        <input type="text" name="meaning" value="{{ $v->meaning }}" required
                               class="w-full bg-gray-50 border border-gray-200 rounded px-3 py-2 pl-8 focus:bg-white focus:border-custom-main focus:ring-1 focus:ring-custom-main outline-none transition text-gray-600">
                    </div>

                    {{-- NÚT LƯU (FORM 1) --}}
                    <button type="submit" 
                            class="w-full md:w-auto px-4 py-2 bg-blue-100 text-blue-700 font-bold rounded hover:bg-blue-600 hover:text-white transition whitespace-nowrap">
                         Lưu
                    </button>
                </form>

                {{-- FORM XÓA (Nằm tách biệt bên cạnh) --}}
                <form action="{{ route('deleteVocab', ['id' => $v->vocabID]) }}" method="POST" 
                      onsubmit="return confirm('Bạn chắc chắn muốn xóa từ này?');" 
                      class="w-full md:w-auto">
                    @csrf 
                    @method('DELETE')
                    
                    {{-- NÚT XÓA (FORM 2) --}}
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-100 text-red-600 font-bold rounded hover:bg-red-500 hover:text-white transition whitespace-nowrap">
                        <i class="fas fa-trash-alt mr-1"></i>
                    </button>
                </form>

            </div>

        @empty
            {{-- Giao diện khi chưa có từ nào (Giữ nguyên) --}}
            <div class="text-center py-12 bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl">
                <div class="text-4xl text-gray-300 mb-3"><i class="fas fa-folder-open"></i></div>
                <p class="text-gray-500 font-medium">Chưa có từ vựng nào trong bài này.</p>
                <p class="text-sm text-gray-400">Hãy thêm từ mới ở khung phía trên!</p>
            </div>
        @endforelse
    </div>
</div>

@endsection