<?php

namespace App\Http\Controllers;

use App\Faculty;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacultyController extends Controller
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
                'data' => Faculty::all(),
                'message' => 'get Facultys success'
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
            Faculty::create([
                'name' => $request->input('name')
            ]);

            return response()->json(['message' => 'create Faculty success'], 203);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'create aborted!'], 409);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function show(Faculty $faculty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function edit(Faculty $faculty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $faculty = Faculty::findOrFail($id);
            $faculty->update(['name' => $request->input('name')]);

            return response()->json(['message' => 'update Faculty success', 'data' => $faculty], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Update Aborted!'], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Faculty  $faculty
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Faculty::destroy($id);

            return response()->json(['message' => 'delete Faculty success'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'delete aborted!'], 409);
        }
    }
}
