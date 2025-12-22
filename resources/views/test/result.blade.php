<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả bài thi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
        {{-- Header màu sắc dựa trên kết quả --}}
        <div class="{{ $resultData['percentage'] >= 80 ? 'bg-green-500' : ($resultData['percentage'] >= 50 ? 'bg-blue-500' : 'bg-red-500') }} p-8 text-center text-white">
            <div class="text-6xl mb-2">
                @if($resultData['percentage'] >= 80)
                <i class="fas fa-trophy animate-bounce"></i>
                @elseif($resultData['percentage'] >= 50)
                <i class="fas fa-star"></i>
                @else
                <i class="fas fa-book-open"></i>
                @endif
            </div>
            <h1 class="text-3xl font-bold">
                @if($resultData['percentage'] >= 80) Xuất sắc!
                @elseif($resultData['percentage'] >= 50) Làm tốt lắm!
                @else Cần cố gắng hơn!
                @endif
            </h1>
            <p class="opacity-90 mt-1">Lần chơi thứ: #{{ $resultData['attempt'] }}</p>
        </div>

        <div class="p-8">
            {{-- Grid thống kê --}}
            <div class="grid grid-cols-2 gap-4 mb-8">
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                    <p class="text-gray-500 text-sm mb-1">Số câu đúng</p>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ $resultData['score'] }} <span class="text-sm text-gray-400">/ {{ $resultData['completed'] }}</span>
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                    <p class="text-gray-500 text-sm mb-1">Tỷ lệ đúng</p>
                    <p class="text-2xl font-bold {{ $resultData['percentage'] >= 50 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $resultData['percentage'] }}%
                    </p>
                </div>
            </div>

            <div class="text-center text-gray-500 text-sm mb-8">
                Bạn đã hoàn thành <strong>{{ $resultData['completed'] }}</strong> trên tổng số <strong>{{ $resultData['total_test'] }}</strong> câu hỏi của bộ đề này.
            </div>

            {{-- Các nút hành động --}}
            <div class="space-y-3">
                <form action="{{ route('favourite', ['id' => $resultData['testID']]) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full py-3 mb-2 rounded-lg font-bold border-2 transition flex items-center justify-center
            {{ $resultData['isFavorited'] 
                ? 'bg-pink-50 border-pink-500 text-pink-600 hover:bg-pink-100' 
                : 'bg-white border-gray-300 text-gray-500 hover:border-pink-400 hover:text-pink-500' 
            }}">

                        {{-- Icon đổi theo trạng thái --}}
                        <i class="{{ $resultData['isFavorited'] ? 'fas' : 'far' }} fa-heart text-xl mr-2"></i>

                        <span>{{ $resultData['isFavorited'] ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}</span>
                    </button>
                </form>
                <a href="{{ route('retryTest', ['id' => $resultData['testID']]) }}"
                    class="block w-full py-3 bg-cyan-600 text-white text-center font-bold rounded-lg shadow-md hover:bg-cyan-700 transition transform hover:-translate-y-0.5">
                    <i class="fas fa-redo-alt mr-2"></i> Làm lại bài này
                </a>
                <a href="{{ route('create') }}" class="block w-full py-3 bg-gray-800 text-white text-center font-bold rounded-lg hover:bg-gray-900 transition">
                    <i class="fas fa-plus-circle mr-2"></i> Tạo bài Test mới
                </a>

                {{-- Nếu bạn có route trang chủ --}}
                <a href="{{ route('home') }}" class="block w-full py-3 bg-gray-100 text-gray-700 text-center font-bold rounded-lg hover:bg-gray-200 transition">
                    Về trang chủ
                </a>
            </div>
        </div>
    </div>

</body>

</html>