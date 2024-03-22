@extends('layout')
@section('content')

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>
                {{$error}}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

<form action="/article" method="post">
    @csrf
    <div class="form-group">
        <label for="exampleInputDate">Date</label>
        <input type="date" class="form-control" id="exampleInputDate" name="date">
  </div>
  <div class="form-group">
        <label for="exampleInputName">Name</label>
        <input type="text" class="form-control" id="exampleInputName" name="name">
  </div>   
   <div class="form-group">
        <label for="exampleInputName">Desc</label>
        <input type="text" class="form-control" id="exampleInputName" name="desc">
  </div>
  <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection