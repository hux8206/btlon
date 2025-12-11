@extends('test.vocabDetail.main') 
@section('content') 
<h5 style="font-weight: bold">Edit Vocabulary</h5> 
<div class="container"> 
    <div class="row"> 
        <div class="col-sm-10"> 
                <form action="{{ route('postEdit',['index' => $index]) }}" method="post"> 
                  @csrf
                  @method('PUT')
                  <div class="form-group">
                    <label for="word" style="font-weight: bold">Word :</label>
                    <input type="text" name="word" id="word" class="form-control" value="{{ $word }}">
                    @error('word')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="meaning" style="font-weight: bold">Meaning :</label>
                    <input type="text" name="meaning" id="meaning" class="form-control" value="{{ $meaning }}">
                    @error('meaning')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group"> 
                    <input class="btn btn-info btn-sm" type="submit" value="Save">
                    <a class="btn btn-secondary btn-sm " href="{{ route('list') }}">Cancel</a>
                </div>
                </form> 
        </div> 
    </div>  
</div> 
@endsection