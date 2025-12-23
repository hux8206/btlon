@extends('layout.main')
@section('content')

<div class="max-w-5xl mx-auto py-10 px-4">

    {{-- HEADER CARD --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
        {{-- Phần màu nền trên cùng --}}
        <div class="bg-gradient-to-r from-custom-dark to-custom-main p-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-extrabold mb-2">{{ $test->title }}</h1>
                <div class="flex items-center gap-4 text-sm opacity-90">
                    <span class="bg-blue bg-opacity-20 px-3 py-1 rounded">ID: {{ $test->testID }}</span>
                    <span><i class="fas fa-clock mr-1"></i> {{ $test->timeEachQuestion }} giây/câu</span>
                    <span><i class="{{ $test->mode == 0 ? 'fas fa-globe' : 'fas fa-lock' }} mr-1"></i> {{ $test->mode == 0 ? 'Công khai' : 'Riêng tư' }}</span>
                </div>
            </div>
            
            {{-- Nút Chỉnh sửa thông tin (Mở Modal) --}}
            <button onclick="document.getElementById('edit-modal').classList.remove('hidden')" 
                    class="mt-4 md:mt-0 px-6 py-2 bg-white text-custom-main font-bold rounded-full shadow hover:bg-gray-100 transition">
                <i class="fas fa-pen mr-2"></i> Chỉnh sửa
            </button>
        </div>

        {{-- Phần nội dung dưới Header --}}
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Thống kê nhanh --}}
            <div class="flex items-center justify-around bg-gray-50 rounded-xl p-4 border border-gray-100">
                <div class="text-center">
                    <p class="text-gray-500 text-xs uppercase font-bold">Số câu hỏi</p>
                    <p class="text-2xl font-bold text-custom-dark">{{ $test->quantity }}</p>
                </div>
                <div class="w-px h-10 bg-gray-300"></div>
                <div class="text-center">
                    <p class="text-gray-500 text-xs uppercase font-bold">Lượt tham gia</p>
                    <p class="text-2xl font-bold text-custom-dark">{{ $playerHistory->count() }}</p>
                </div>
            </div>

            {{-- Nút Chỉnh sửa từ vựng (Để sau làm) --}}
            <div class="flex items-center">
                <a href="{{ route('editVocab',['id'=> $test->testID]) }}" class="block w-full text-center py-4 border-2 border-dashed border-gray-300 text-gray-500 font-bold rounded-xl hover:border-custom-main hover:text-custom-main transition group">
                    <div class="text-2xl mb-1 group-hover:scale-110 transition"><i class="fas fa-layer-group"></i></div>
                    Chỉnh sửa từ vựng
                </a>
            </div>
        </div>
    </div>

    {{-- BẢNG THỐNG KÊ NGƯỜI CHƠI --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-history mr-2 text-custom-main"></i> Lịch sử người tham gia
            </h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white text-gray-500 text-xs uppercase font-bold border-b">
                    <tr>
                        <th class="p-4 pl-6">Người chơi</th>
                        <th class="p-4 text-center">Lần thử</th>
                        <th class="p-4 text-center">Điểm số</th>
                        <th class="p-4 text-right pr-6">Thời gian nộp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($playerHistory as $h)
                    <tr class="hover:bg-blue-50 transition">
                        <td class="p-4 pl-6 font-bold text-gray-700">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-500">
                                    <i class="fas fa-user"></i>
                                </div>
                                {{ $h->fullName }}
                            </div>
                        </td>
                        <td class="p-4 text-center text-gray-500">#{{ $h->numOfPlay }}</td>
                        <td class="p-4 text-center">
                            <span class="px-3 py-1 rounded-full font-bold {{ $h->correct_question == $h->question_completed ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $h->correct_question }} / {{ $h->question_completed }}
                            </span>
                        </td>
                        <td class="p-4 text-right pr-6 text-gray-400">
                            {{ \Carbon\Carbon::parse($h->done_at)->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400 italic">
                            Chưa có ai chơi bài này. Hãy chia sẻ ID: <b>{{ $test->testID }}</b> cho mọi người nhé!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL CHỈNH SỬA THÔNG TIN (Popup ẩn) --}}
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all scale-100">
        <div class="p-6 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Cập nhật thông tin</h3>
            <button onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form action="{{ route('updateTest', ['id' => $test->testID]) }}" method="POST" class="p-6">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Tên bài ôn tập</label>
                <input type="text" name="title" value="{{ $test->title }}" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-custom-main focus:border-transparent outline-none">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Thời gian (giây/câu)</label>
                <input type="number" name="time" value="{{ $test->timeEachQuestion }}" min="10" required
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-custom-main focus:border-transparent outline-none">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2">Chế độ hiển thị</label>
                <select name="mode" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-custom-main outline-none bg-white">
                    <option value="0" {{ $test->mode == 0 ? 'selected' : '' }}>🌍 Công khai (Public)</option>
                    <option value="1" {{ $test->mode == 1 ? 'selected' : '' }}>🔒 Riêng tư (Private)</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Riêng tư: Chỉ những người có Mã ID mới vào được.</p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" 
                        class="px-5 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition">Hủy</button>
                <button type="submit" 
                        class="px-5 py-2 bg-custom-main text-white font-bold rounded-lg hover:bg-custom-dark transition">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

@endsection