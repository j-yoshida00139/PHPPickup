<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Page2ch;

class PostController extends Controller
{
    public function index($linkId){
        $posts = Page2ch::where('link2ches_id',$linkId)->orderBy('postedTime', 'asc')->get();
        return view('page2ch')->with('posts',$posts);
    }
}
