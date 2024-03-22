@extends('layout')
@section('content')


<div class="card" style="width: 67rem;">
  <div class="card-body">
    <h5 class="card-title">{{$article->name}}</h5>
    <p class="card-text">{{$article->desc}}</p>
    <div class=btn-group>
    <a href="/article/{{$article->id}}/edit" class="btn btn-primary mr-3">Edit Article </a>
    <form action="/article/{{$article->id}}" method="post">
    @method("DELETE")
    @csrf
    <button type= "sumbit" class="btn btn-dark"> Delete Article</button>
    </form>
</div>
</div>
</div>

<h4>Comments</h4>
<form action="/comment" method="post">
    @csrf
    <input tipy="hidden" name="article_id" value= "{{$article->article_id}}"> </input>
  <div class="form-group">
        <label for="exampleInputName">Name</label>
        <input type="text" class="form-control" id="exampleInputName" name="title" value= "{{$article->title}}">
  </div>   
   <div class="form-group">
        <label for="exampleInputName">Desc</label>
        <input type="text" class="form-control" id="exampleInputName" name="text" value= "{{$article->text}}">
  </div>
  <button type="submit" class="btn btn-primary">Create Comment</button>
</form>
@foreach($comments as $comment)
<div class="card" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">{{$comment->name}}</h5>
    <p class="card-text">{{$comment->desc}}</p>
    <div class=btn-group>
    <a href="/article/{{$article->id}}/edit" class="btn btn-primary">Edit Comment</a>
    <form action="/article/{{$article->id}}" method="post">
    @method("DELETE")
    @csrf
    <button type= "sumbit" class="btn btn-dark"> Delete </button>
    </form>
</div>
  </div>
</div>
@endforeach
@endsection