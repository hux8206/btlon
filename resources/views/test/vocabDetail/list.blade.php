@extends('test.vocabDetail.main') 

@section('content') 
<h5 style="font-weight: bold">List Vocabulary
</h5>
<div class="container"> 
    <div class="row"> 
        <div class="col-sm-10">
            <p class="d-flex justify-content-end"> 
                <a class="btn btn-info btn-sm mr-2" href="{{ route('add') }}">Add Vocabulary</a> 
                <a class="btn btn-info btn-sm" href="{{ route('create') }}">Save change</a> 
            </p>
            @if (session('message'))
                 <div class="alert alert-success">
                     {{ session('message') }}
                 </div>
            @endif
            <table class="table table-hover table-border"> 
                <thead> 
                    <tr> 
                        <th>ID</th> 
                        <th>Word</th> 
                        <th>Meaning</th>
                        <th>Action</th> 
                    </tr> 
                </thead> 
                <tbody> 
                        @forelse($rows as $index => $row)
                            @php
                                $part = explode(":", $row);
                                $word = trim($part[0]);
                                $meaning = isset($part[1]) ? trim($part[1]) : '';
                            @endphp
                        <tr> 
                            <td>{{ $index + 1 }}</td> 
                            <td>{{ $word }}</td>
                            <td>{{ $meaning }}</td> 
                            <td> 
                                <a href="{{ route('edit', ['index' => $index]) }}" class="btn btn-info btn-sm">Edit</a>     
                                <form class="d-inline-block" action="{{route('delete', ['index' => $index])}}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="Delete" class="btn btn-warning btn-sm">
                            </form>
                            </td> 
                        </tr>
                        @empty
                            <tr>
                                <td colspan="4">Hien dang trong</td>
                            </tr> 
                        @endforelse 
                </tbody> 
            </table> 
            
        </div> 
    </div> 
</div> 
@endsection
                                   