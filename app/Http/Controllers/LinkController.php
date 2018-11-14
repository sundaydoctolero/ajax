<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\LinkRequest;
use App\Link;


class LinkController extends Controller
{
    public function index(){
        $links = Link::all();
        return view('links.index',compact('links'));
    }

    public function store(LinkRequest $request){
        $link = Link::create($request->all());
        return response()->json($link);
    }

    public function destroy(Link $links){
        $links->delete();
        return response()->json('Deleted');
    }

    public function show(Link $links){
        return response()->json($links);
    }

    public function update(Link $links,Request $request){
        $links->update($request->all());
        return response()->json($links);
    }

}
