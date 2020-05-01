<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    protected function respondWithToken($token)
    {
        return response()->json([
          'error' => false,
          'token_type' => 'Bearer',
          'access_token' => $token,
          'expires_in' => $this->guard()->factory()->getTTL() * 60,
          'user' => JWTAuth::user(),
          'message' => 'Login Berhasil !'
        ]);
    }
    private function guard()
  	{
    	return Auth::guard('api');            
  	}
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'email' => 'string',
            'username' => 'string',
            'password' => 'required|string',
        ]);

        $loginType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->email,
            'password' => $request->password
        ];

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);
            $user->hp = $request->input('hp');
            $user->address = $request->input('address');
            $user->status = $request->input('status');
            $user->username = $request->input('username');
            $user->majors = $request->input('majors');
            $user->faculty = $request->input('faculty');

            $user->save();

            $user->assignRole('user');

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Berhasil Register!'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Registrasi Gagal!'], 409);
        }
    }

    public function registerAdmin(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->assignRole('admin');

            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'Register Admin Berhasil!'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'Register Admin Gagal!'], 409);
        }
    }
}
