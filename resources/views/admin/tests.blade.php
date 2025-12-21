@extends('admin.main')

@section('title','TESTS LIST')

@section('page-title','Tests list')

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
                        <th>testID</th>
                        <th>userID</th>
                        <th>title</th>
                        <th>Time each question</th>
                        <th>quantity</th>
                        <th>mode</th>
                        <th>day created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $t)
                    <tr>
                        <td>{{ $t-> testID }}</td>
                        <td>{{ $t-> userID }}</td>
                        <td>{{ $t-> title }}</td>
                        <td>{{ $t-> timeEachQuestion }}</td>
                        <td>{{ $t-> quantity }}</td>
                        <td>{{ $t-> mode }}</td>
                        <td>{{ $t-> dayCreated }}</td>
                        <td>
                            <a href="{{ route('show') }}" class="btn btn-info btn-sm">Show</a>
                            <form class="d-inline-block" action="{{route('deleteTest', $t -> testID)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-danger btn-sm">
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
            <div>{{ $tests->links('vendor.pagination.bootstrap-5') }}</div>
        </div>
    </div>
</div>
@endsection