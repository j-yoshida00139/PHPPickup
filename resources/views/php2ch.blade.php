@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div id="catch_contents">
<?php
    include_once(getcwd().'/../resources/views/simple_html_dom.php');
    $curl = curl_init(); 
    curl_setopt($curl, CURLOPT_URL, 'http://find.2ch.sc/?STR=php&TYPE=TITLE&x=29&y=9&BBS=ALL&ENCODING=SJIS&COUNT=50');  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);  
    $str = curl_exec($curl); 
    curl_close($curl); 
    $html= str_get_html($str);
        ////////////////////　↓↓ここで取りたい要素を指定 ////////////////////
    foreach($html -> find('dl dt a') as $list){
       ////////////////////  ここから表示部分////////////////////////////////
            echo "<ul class='news'>"; ///////任意のクラスを付けている
            echo "<li class='news'>";
            echo "<p>";
            echo "<a href='";
            echo $list->href;
            echo "'>";
            echo $list->outertext;
            echo "</a>";
            echo "</p>";
            echo "</li>";
            echo "</ul>";
    }
    $html -> clear();
    unset($html);
?>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection



