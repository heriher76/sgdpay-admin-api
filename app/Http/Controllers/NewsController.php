<?php

namespace App\Http\Controllers;

use App\News;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $newsNews = News::orderBy('created_at', 'desc')->get();

            return response()->json([
                'data' => $newsNews,
                'message' => 'get news success'
            ], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'get news aborted!'], 409);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $input = $request->all();

            ($input['slug']) ? $slug = $input['slug'] : $slug = str_slug($input['title'], '-');
            ($request->file('thumbnail') != null) ? $namaThumbnail = Str::random(32).'.'.$request->file('thumbnail')->getClientOriginalExtension() : $namaThumbnail = null;

            $news = News::create([
                'title' => $input['title'],
                'description' => $input['description'],
                'slug' => $slug,
                'thumbnail' => $namaThumbnail,
                'publish_status' => $input['publish_status'],
                'user_id' => Auth::user()->id
            ]);
            
            ($request->file('thumbnail') != null) ? $request->file('thumbnail')->move(public_path('news-thumbnail'), $namaThumbnail) : null ;

            return response()->json([
                'data' => $news,
                'message' => 'create news success'
            ], 203);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Create News Aborted!'], 409);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        //
    }

    public function show($id)
    {
        try{
            $news = News::findOrFail($id);

            return response()->json([
                'data' => $news,
                'message' => 'get detail news success'
            ], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Detail News Aborted!'], 409);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $input = $request->all();

            $News = News::where('id', $id)->first();

            ($input['slug']) ? $slug = $input['slug'] : $slug = str_slug($input['title'], '-');
            if ($request->file('thumbnail') != null) {
                $namaThumbnail = Str::random(32).'.'.$request->file('thumbnail')->getClientOriginalExtension();
                
                if (isset($News->thumbnail)) {
                    unlink(public_path('news-thumbnail/'.$News->thumbnail));
                }
                $request->file('thumbnail')->move(public_path("news-thumbnail/"), $namaThumbnail);  

                $News->update([
                    'title' => $input['title'],
                    'description' => $input['description'],
                    'thumbnail' => $namaThumbnail,
                    'publish_status' => $input['publish_status'],
                    'slug' => $input['slug']
                ]);
            }else{
                $News->update([
                    'title' => $input['title'],
                    'description' => $input['description'],
                    'publish_status' => $input['publish_status'],
                    'slug' => $input['slug']
                ]);
            }

            return response()->json([
                'data' => $News,
                'message' => 'update news success'
            ], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Update News Aborted!'], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $News = News::where('id', $id)->first();

        if (isset($News->thumbnail)) {
            unlink(public_path('news-thumbnail/'.$News->thumbnail));
        }
        
        $News->destroy($id);

        return response()->json(['message' => 'delete news success'], 200);
    }
}
