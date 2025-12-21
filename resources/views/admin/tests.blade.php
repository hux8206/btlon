@extends('admin.main')

@section('title','TESTS LIST')

@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-10">
            <table class="table table-hover table-border">
                @if (session('error'))
                <div class="alert alert-success">
                    {{ session('error') }}
                </div>
                @endif
                <thead>
                    <tr>
                        <th>userID</th>
                        <th>email</th>
                        <th>fullname</th>
                        <th>status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>{{ $u-> userID }}</td>
                        <td>{{ $u-> email }}</td>
                        <td>{{ $u-> fullName }}</td>
                        <td>
                            @if($u->status)
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                Hoạt động
                            </span>
                            @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                Đã khóa
                            </span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('status', ['id' => $u->userID]) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')

                                @if($u->status)
                                {{-- Nút Khóa --}}
                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 transition"
                                    title="Vô hiệu hóa user này"
                                    onclick="return confirm('Bạn có chắc chắn muốn KHÓA tài khoản (ID: {{ $u->userID }}) này không?');">
                                    <i class="fas fa-lock text-lg"></i>
                                </button>
                                @else
                                {{-- Nút Mở khóa --}}
                                <button type="submit"
                                    class="text-green-500 hover:text-green-700 transition"
                                    title="Kích hoạt user này"
                                    onclick="return confirm('Bạn có chắc chắn muốn MỞ KHÓA tài khoản (ID: {{ $u->userID }}) này không?');">
                                    <i class="fas fa-unlock text-lg"></i>
                                </button>
                                @endif
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">Hien dang trong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div>{{ $users->links('vendor.pagination.bootstrap-5') }}</div>
        </div>
    </div>
</div>
@endsection