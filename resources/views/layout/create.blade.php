@extends('layout.main')

@section('title','Create Test')

@section('css')
<link rel="stylesheet" href="{{ asset('create.css') }}">
@endsection

@section('content')

<form method="post" href="#">
    <div>
        <label>Tu Vung</label>
        <input type="file" value="upload">
    </div>
    <div>
        <label>Thoi Gian</label></td>
        <input type="number" min="1" max="100" step="1">
    </div>
    <div>
        <label>Tieu De</label>
        <input type="text">
    </div>
    <div>
        <label>So Luong</label></td>
        <input type="number" min="#" max="#" step="1">
        <input type="checkbox" value="Choose all">
    </div>
    <div>
        <label>Che Do</label></td>
        <select>
            <option value="public" name="public">Public</option>
            <option value="private" name="private">Private</option>
        </select>
    </div>
    <div><button>Tao bai do</button></div>
</form>
@endsection