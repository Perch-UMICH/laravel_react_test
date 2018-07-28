<?php

namespace App\Http\Controllers;

use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::all();
        return $this->outputJSON($tags, 'Retrieved tags');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tag = Tag::where('name', $request['name']);
        if ($tag) {
            return $this->outputJSON($tag, 'Error: tag of this name already exists');
        }

        $tag = new Tag([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);
        $tag->save();
        return $this->outputJSON($tag, 'Tag definition created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return $this->outputJSON($tag, 'Retrieved tag');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }

    // Searches for tags that are a close match to the requested name
    public function search_matching_tags(Request $request)
    {
        $input = $request->all();
        $query = $input['query'];
        $tags = Tag::all()->pluck('name')->toArray();
        $selected = $this->exact_match($query, $tags);

        return $this->outputJSON($selected, 'Returned closest matching tags');
    }
}
