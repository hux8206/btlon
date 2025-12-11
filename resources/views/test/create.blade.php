@extends('layout.main')

@section('title','Create Test')

@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card shadow-sm">

            <div class="card-body">
                <form action="{{ route('postCreate') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <!--Tieu de-->
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề bài dò</label>
                        <input type="text"
                            class="form-control @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            placeholder="Ví dụ: Từ vựng Unit 1"
                            value="{{ old('title') }}">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!--file tu vung-->
                    <div class="row mb-3">
                        <label for="vocabFile" class="form-label">File từ vựng</label>
                        <div class="col-md-6">
                            <input class="col-md-6 form-control @error('vocabFile') is-invalid @enderror"
                                type="file"
                                id="vocabFile"
                                name="vocabFile"
                                accept=".csv, .txt"
                                value="{{ old('vocabFile') }}">
                            <div class="form-text text-muted">
                                <i class="fa-regular fa-file-lines me-1"></i>
                                Định dạng mẫu: <code>Từ vựng:Nghĩa</code> (Mỗi từ 1 dòng)
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-center mb-4"><button type="submit"
                                formaction="{{ route('uploadAndEdit') }}"
                                class="btn ">
                                <i class="fa-solid fa-pencil"></i>
                            </button></div>
                        @error('vocabFile')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!--thoi gian-->
                    <div class="mb-3">
                        <label for="timeEachQuestion" class="form-label">Thời gian (giây/câu)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-regular fa-clock"></i></span>
                            <input type="number"
                                class="form-control @error('timeEachQuestion') is-invalid @enderror"
                                id="timeEachQuestion"
                                name="timeEachQuestion"
                                value="{{ old('timeEachQuestion', ) }}"
                                min="5">
                            @error('timeEachQuestion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!--so luong-->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Số lượng câu hỏi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa-solid fa-list-ol"></i></span>
                                <input type="number"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    id="quantity"
                                    name="quantity"
                                    value="{{ old('quantity', ) }}"
                                    min="1">
                                @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center mt-10">
                            <input class="form-check-input" type="checkbox" id="all" name="all" value="1">
                            <label class="form-check-label ms-1" for="all">Tất cả</label>
                        </div>

                    </div>

                    <!-- che do-->
                    <div class="mb-4">
                        <label class="form-label d-block">Chế độ hiển thị</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="mode" id="modePublic" value="0" checked>
                            <label class="btn btn-outline-primary" for="modePublic">
                                <i class="fa-solid fa-globe me-1"></i> Công khai
                            </label>

                            <input type="radio" class="btn-check" name="mode" id="modePrivate" value="1">
                            <label class="btn btn-outline-secondary" for="modePrivate">
                                <i class="fa-solid fa-lock me-1"></i> Riêng tư
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-create text-white shadow">
                            <i class="fa-solid fa-check me-2"></i> Hoàn tất & Tạo bài
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-light text-muted">
                            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại trang chủ
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection