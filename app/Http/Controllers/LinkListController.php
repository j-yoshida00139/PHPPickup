<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Link2ch;

class LinkListController extends Controller
{
    public function index(){
        $links = Link2ch::orderBy('postedDate', 'desc')->take(50)->get();
        return view('php2ch')->with('links',$links);
    }
}
