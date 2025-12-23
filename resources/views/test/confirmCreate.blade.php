<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Bài Dò</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('confirm.css') }}">
</head>
<body>

<div class="card-detail">
    <div class="test-id">ID: <span class="text-primary">{{ $test->testID }}</span>
    </div>

    <h1 class="test-title">{{ $test->title }}</h1>

    <div class="info-list">
        <div class="info-item">
            <span class="info-label">Ngày tạo:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($test->dayCreated)->format('d/m/Y') }}</span>
        </div>

        <div class="info-item">
            <span class="info-label">Thời gian mỗi câu:</span>
            <span class="info-value">{{ $test->timeEachQuestion }} giây</span>
        </div>

        <div class="info-item">
            <span class="info-label">Số lượng câu hỏi:</span>
            <span class="info-value">{{ $test->quantity }} câu</span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Chế độ:</span>
            <span class="info-value">
                @if($test->mode == 0)
                    <span class="badge bg-success">Công khai</span>
                @else
                    <span class="badge bg-secondary">Riêng tư</span>
                @endif
            </span>
        </div>
    </div>

    <a href="{{ route('doTest') }}" class="btn btn-start">
        DÒ BÀI
    </a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>