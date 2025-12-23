@extends('admin.main')
@section('title', 'Admin statistic')

@section('content')

{{-- 1. CÁC THẺ THỐNG KÊ (CARDS) --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 font-bold uppercase">Tổng người tham gia</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
            <i class="fas fa-users text-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 font-bold uppercase">Tổng bài test</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalTests }}</h3>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
            <i class="fas fa-book text-xl"></i>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 font-bold uppercase">Tổng số lượt dò</p>
            <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPlays }}</h3>
        </div>
        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
            <i class="fas fa-gamepad text-xl"></i>
        </div>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- 2. BIỂU ĐỒ (Chiếm 2 phần) --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h4 class="font-bold text-gray-700 mb-4 text-lg">
            <i class="fas fa-chart-line mr-2 text-custom-main"></i> Số lượng bài thi mới (7 ngày qua)
        </h4>
        <div class="relative h-72">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    {{-- 3. TOP USER (Chiếm 1 phần) --}}
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h4 class="font-bold text-gray-700 mb-4 text-lg">
            <i class="fas fa-trophy mr-2 text-yellow-500"></i> Top Thành viên
        </h4>
        <div class="space-y-4">
            @foreach($topUsers as $index => $u)
            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition">
                <div class="flex items-center">
                    {{-- Avatar giả / Icon --}}
                    <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center font-bold text-gray-500 mr-3 shadow-sm">
                        {{ substr($u->fullName, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800">{{ $u->fullName }}</p>
                        <p class="text-xs text-gray-500">{{ $u->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="block text-sm font-bold text-custom-main">{{ $u->total_played }}</span>
                    <span class="text-xs text-gray-400">lượt</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- SCRIPT VẼ BIỂU ĐỒ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- 1. Load thư viện Chart.js (Dùng bản 4.x ổn định) --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Chỉ chạy code khi trang web đã tải xong hoàn toàn
    document.addEventListener("DOMContentLoaded", function() {
        
        // 2. Nhận dữ liệu từ Laravel một cách an toàn
        // Nếu biến null hoặc rỗng thì mặc định là mảng rỗng []
        const labels = {!! !empty($chartLabels) ? json_encode($chartLabels) : '[]' !!};
        const data = {!! !empty($chartData) ? json_encode($chartData) : '[]' !!};

        // Debug: Bạn hãy mở F12 -> Console để xem dòng này có hiện dữ liệu không
        console.log("Labels:", labels);
        console.log("Data:", data);

        // 3. Tìm thẻ Canvas
        const chartCanvas = document.getElementById('myChart');

        if (chartCanvas) {
            const ctx = chartCanvas.getContext('2d');
            
            // Khởi tạo biểu đồ
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Bài thi mới',
                        data: data,
                        borderColor: '#3b82f6', // Màu xanh blue-500
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#3b82f6',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        tension: 0.3, // Độ cong mềm mại
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // Ẩn chú thích
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1, // Chỉ hiện số nguyên (không hiện 1.5 bài thi)
                                precision: 0
                            },
                            grid: {
                                borderDash: [2, 4],
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } else {
            console.error("Lỗi: Không tìm thấy thẻ <canvas id='myChart'> trong HTML.");
        }
    });
</script>

@endsection