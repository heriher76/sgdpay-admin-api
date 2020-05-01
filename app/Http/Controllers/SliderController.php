<?php

namespace App\Http\Controllers;

use App\Slider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $sliders = Slider::orderBy('created_at', 'desc')->get();

            return response()->json([
                'data' => $sliders,
                'message' => 'get sliders success'
            ], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'get sliders aborted!'], 409);
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

            ($request->file('image') != null) ? $namaimage = Str::random(32).'.'.$request->file('image')->getClientOriginalExtension() : $namaimage = null;

            $slider = Slider::create([
                'title' => $input['title'],
                'description' => $input['description'],
                'image' => $namaimage
            ]);

            ($request->file('image') != null) ? $request->file('image')->move(public_path('sliders-image'), $namaimage) : null ;

            return response()->json([
                'data' => $slider,
                'message' => 'create slider success'
            ], 203);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'get slider aborted!'], 409);
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
            $slider = Slider::findOrFail($id);

            return response()->json([
                'data' => $slider,
                'message' => 'get detail slider success'
            ], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Detail Slider Aborted!'], 409);
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
        $input = $request->all();

        $slider = Slider::where('id', $id)->first();

        if ($request->file('image') != null) {
            $namaimage = Str::random(32).'.'.$request->file('image')->getClientOriginalExtension();
            
            if (isset($slider->image)) {
                unlink(public_path('sliders-image/'.$slider->image));
            }
            $request->file('image')->move(public_path("sliders-image/"), $namaimage);  

            $slider->update([
                'title' => $input['title'],
                'description' => $input['description'],
                'image' => $namaimage
            ]);
        }else{
            $slider->update([
                'title' => $input['title'],
                'description' => $input['description']
            ]);
        }

        return response()->json([
            'data' => $slider,
            'message' => 'update slider success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::where('id', $id)->first();

        if (isset($slider->image)) {
            unlink(public_path('sliders-image/'.$slider->image));
        }
        
        $slider->destroy($id);

        return response()->json(['message' => 'delete slider success'], 200);
    }
}
