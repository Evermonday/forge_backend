<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Auth::user()->tags;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newTagsArray = array_map(function($newTag) {
            return ['name' => $newTag];
        }, $request->all());

        $request->user()->tags()->createMany($newTagsArray);
        
        return $request->user()->tags;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        $tag->name = $request->name;
        $tag->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
