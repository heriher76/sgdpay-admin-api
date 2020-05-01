<?php

namespace App\Http\Controllers;

use App\Major;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return response()->json([
                'data' => Major::all(),
                'message' => 'get majors success'
            ], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'get aborted!'], 409);
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
            Major::create([
                'name' => $request->input('name')
            ]);

            return response()->json(['message' => 'create major success'], 203);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'create aborted!'], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function show(Major $major)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function edit(Major $major)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $major = Major::findOrFail($id);
            $major->update(['name' => $request->input('name')]);

            return response()->json(['message' => 'update major success', 'data' => $major], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Update Aborted!'], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Major::destroy($id);

            return response()->json(['message' => 'delete major success'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'delete aborted!'], 409);
        }
    }
}
