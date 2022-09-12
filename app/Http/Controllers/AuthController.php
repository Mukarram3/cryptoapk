<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\sendcode;
use Illuminate\Support\Str;;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','signup','loginadmin']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }
        // return $this->respondWithToken($token);
        return response()->json(['success' => true,'token' => $token, 'authuser' => auth()->user()]);
    }


    public function loginadmin(Request $request){
        // $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt(array('email' => 'admin@gmail.com', 'password' => $request->password))) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }
        else{
            if(auth()->user()->type == "admin"){
                return response()->json(['success' => true,'token' => $token, 'authuser' => auth()->user()]);
            }
            else{
                return response()->json(['success' => false, 'error' => 'Users not authorized. Only Admin can Login'], 401);
            }
        }
        // return $this->respondWithToken($token);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json(User::find($request->id));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['success' => true, 'message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function signup(Request $request){


        $table= User::where('name', $request->name)->orwhere('email', $request->email)->first();
        if($table){
            return response()->json(['success' => false, 'message' => 'Username or Email already exist']);
        }
        else{
            
            // $table= sendcode::where('email', $request->email)->first();

            // if($table->code==$request->code){

                $data=array();
            $data["name"]=$request->name;
            $data["email"]=$request->email;
            $data["paymentaddress"]=Str::random(30);
            $data["password"]=Hash::make($request->password);
            // $data["verified"]=true;
            DB::table('users')->insert($data);

                $credentials = request(['email', 'password']);

                $token = auth()->attempt($credentials);

                return response()->json(['success' => true, 'token' => $token, 'authuser' => auth()->user()]);

            // }

            // else{
            //     return response()->json(['success' => false, 'message' => 'Please enter valide code or resend code']);
            // }

            // return $this->login($request);
        }
       
            }

}
