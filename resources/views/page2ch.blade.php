@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div id="catch_contents">
                    @foreach($posts as $post)
                        <ul class='post'>
                            <li class='post'>
                                <span>{{$post->postedNo}}</span>
                                <span>{{$post->posterName}}</span>
                                <span>{{$post->postedTime}}</span>
                                <span>{{$post->posterId}}</span>
                                <br>
                                {{$post->text}}
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



