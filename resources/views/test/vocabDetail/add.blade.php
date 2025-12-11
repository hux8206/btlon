@extends('test.vocabDetail.main')
@section('content')
<h5 style="font-weight: bold">Add Category</h5>
<div class="container">
    <div class="row">
        <div class="col-sm-10">
            <form action="{{ route('postAdd') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="word" style="font-weight: bold">Word :</label>
                    <input type="text" name="word" id="word" class="form-control">
                    @error('word')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="meaning" style="font-weight: bold">Meaning :</label>
                    <input type="text" name="meaning" id="meaning" class="form-control">
                    @error('meaning')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <input class="btn btn-info btn-sm" type="submit" value="Add vocabulary">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection