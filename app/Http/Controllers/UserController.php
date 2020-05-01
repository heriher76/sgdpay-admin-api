<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    public function getAdmins()
    {
        return response()->json(['admins' => User::role('admin')->get(), 'message' => 'get admin user success'], 200);
    }

    public function index()
    {
        return response()->json(['users' => User::role('user')->get(), 'message' => 'get user success'], 200);
    }

    public function show($id)
    {
        return response()->json(['users' => User::findOrFail($id), 'message' => 'get other user success'], 200);
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    public function destroy($id)
    {
        User::destroy($id);

        return response()->json(['message' =>  'User Deleted Succesfully'], 200);
    }

    public function update(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'string',
            'hp' => 'string',
            'address' => 'string',
            'status' => 'string'
        ]);

        try {
            $iam = Auth::user();

            $input = $request->all();

            $user = User::where('id', $iam->id)->first();
            $user->update([
              'name' => $input['name'],
              'hp' => $input['hp'],
              'address' => $input['address'],
              'status' => $input['status'],
              'email' => $input['email'],
              'majors' => $input['majors'],
              'faculty' => $input['faculty'],
              'username' => $input['username']
            ]);

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Profile Edited Succesfully'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Edit Profile Failed!'], 409);
        }
    }

    public function updateOther(Request $request, $id)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'string',
            'hp' => 'string',
            'address' => 'string',
            'status' => 'string'
        ]);

        try {
            $input = $request->all();

            $user = User::findOrFail($id);
            $user->update([
              'name' => $input['name'],
              'hp' => $input['hp'],
              'address' => $input['address'],
              'status' => $input['status'],
              'email' => $input['email'],
              'majors' => $input['majors'],
              'faculty' => $input['faculty'],
              'username' => $input['username']
            ]);

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Other Profile Edited Succesfully'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Edit Other Profile Failed!'], 409);
        }
    }

    public function editPhoto(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            //
        ]);

        try {
            $iam = Auth::user();

            $user = User::where('id', $iam->id)->first();

            ($request->file('photo') != null) ? $namaPhoto = Str::random(32).'.'.$request->file('photo')->getClientOriginalExtension() : $namaPhoto = null;

            if (isset($user->photo)) {
                unlink(base_path().'/public/photo-profile/'.$user->photo);
            }

            $user->update([
              'photo' => $namaPhoto,
            ]);

            ($request->file('photo') != null) ? $request->file('photo')->move(base_path().('/public/photo-profile'), $namaPhoto) : null;

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Photo Edited Succesfully'], 200);
        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Edit Photo Failed!'], 409);
        }
    }

    public function editPassword(Request $request)
    {
        $this->validate($request, [
            'old_password'     => 'required',
            'new_password'     => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $data = $request->all();

        if(app('hash')->check($data['old_password'], Auth::user()->password)){
            try {
                $iam = Auth::user();

                $iam->password = app('hash')->make($data['new_password']);
                $iam->save();

                //return successful response
                return response()->json(['message' => 'Password Edited Succesfully'], 200);
            } catch (\Exception $e) {
                //return error message
                return response()->json(['message' => 'Edit Password Failed!'], 409);
            }
        }else{
            //return error message
            return response()->json(['message' => 'You Have Entered Wrong Password!'], 409);
        }
    }
}
