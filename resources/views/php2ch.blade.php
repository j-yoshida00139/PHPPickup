@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div id="catch_contents">
                    @foreach($links as $link)
                        <ul class='news'>
                            <li class='news'>
                                <span>【{{$link->postedDate}}】</span>
                                <a href="/{{$link->id}}" >{{$link->text}}</a>
<!--                                <a href="{{$link->url}}" >{{$link->text}}</a>-->
                            </li>
                        </ul>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



