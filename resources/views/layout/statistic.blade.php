@extends('layout.main')
@section('title','Statistic')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 bg-gray-50 min-h-screen">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Bảng Thống Kê</h1>
        <p class="text-gray-500">Tổng quan quá trình học tập của bạn.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-blue-500 flex items-center">
            <div class="p-4 bg-blue-100 text-blue-600 rounded-full mr-4">
                <i class="fas fa-clipboard-check text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase">Tổng bài đã ôn</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalTestsDone }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-green-500 flex items-center">
            <div class="p-4 bg-green-100 text-green-600 rounded-full mr-4">
                <i class="fas fa-bullseye text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase">Độ chính xác TB</p>
                <p class="text-3xl font-bold text-gray-800">{{ $averageAccuracy }}%</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border-b-4 border-pink-500 flex items-center">
            <div class="p-4 bg-pink-100 text-pink-600 rounded-full mr-4">
                <i class="fas fa-heart text-2xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium uppercase">Bài yêu thích</p>
                <p class="text-3xl font-bold text-gray-800">{{ $totalFavorites }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-line mr-2 text-custom-main"></i> Hoạt động 7 ngày qua
            </h2>
            <div class="relative" style="height: 300px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-chart-pie mr-2 text-custom-main"></i> Tỷ lệ Đúng / Sai toàn bộ
            </h2>
            <div class="relative flex justify-center" style="height: 300px;">
                <canvas id="accuracyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Hoạt động gần đây</h2>
            <a href="{{ route('history') }}" class="text-custom-main text-sm font-semibold hover:underline">Xem tất cả</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs leading-normal">
                    <tr>
                        <th class="py-3 px-6">Bài Test</th>
                        <th class="py-3 px-6 text-center">Kết quả</th>
                        <th class="py-3 px-6 text-right">Thời gian</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse($recentActivity as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="py-3 px-6 font-medium">{{ $item->title }}</td>
                        <td class="py-3 px-6 text-center">
                            <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-bold">
                                {{ $item->correct_question }} / {{ $item->question_completed }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-right text-gray-500">
                            {{ \Carbon\Carbon::parse($item->done_at)->diffForHumans() }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="py-4 text-center text-gray-500">Chưa có hoạt động nào gần đây.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- SCRIPT VẼ BIỂU ĐỒ --}}
<script>
    // Lấy dữ liệu từ PHP truyền sang qua json_encode
    // Thay thế đoạn cũ bằng đoạn này:
    // Thay thế 3 dòng cũ bằng đoạn này:
    // Cách chuẩn nhất (Laravel 8/9/10+):
    const activityLabels = @json($chartLabels);
    const activityData = @json($chartData);
    const accuracyData = @json($accuracyChartData);
    // --- Cấu hình Biểu đồ Đường (Activity Chart) ---
    const ctxActivity = document.getElementById('activityChart').getContext('2d');
    // Tạo gradient màu cho đẹp
    let gradient = ctxActivity.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(0, 199, 224, 0.5)'); // Màu custom-main mờ
    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

    new Chart(ctxActivity, {
        type: 'line', // Loại biểu đồ đường
        data: {
            labels: activityLabels,
            datasets: [{
                label: 'Số bài đã làm',
                data: activityData,
                borderColor: '#00c7e0', // Màu đường kẻ
                backgroundColor: gradient, // Màu nền gradient bên dưới
                tension: 0.4, // Độ cong của đường (cho mềm mại)
                fill: true, // Tô màu bên dưới đường
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#00c7e0',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    } // Chỉ hiện số nguyên trên trục Y
                }
            },
            plugins: {
                legend: {
                    display: false
                } // Ẩn chú thích cho gọn
            }
        }
    });

    // --- Cấu hình Biểu đồ Tròn (Accuracy Chart) ---
    const ctxAccuracy = document.getElementById('accuracyChart').getContext('2d');
    new Chart(ctxAccuracy, {
        type: 'doughnut', // Loại biểu đồ bánh quy (tròn rỗng ruột)
        data: {
            labels: ['Chính xác', 'Chưa chính xác'],
            datasets: [{
                data: accuracyData,
                // Màu xanh lá cho đúng, màu xám/đỏ nhẹ cho sai
                backgroundColor: ['#10B981', '#E5E7EB'],
                hoverBackgroundColor: ['#059669', '#D1D5DB'],
                borderWidth: 0,
                cutout: '70%' // Độ rỗng ở giữa
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                } // Chú thích nằm dưới
            }
        }
    });
</script>
@endsection