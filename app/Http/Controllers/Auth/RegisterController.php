<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
Use Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\RegisterUserRequest;
use Redirect;

class RegisterController extends Controller
{
    //

    public function index()
    { 
        return view('auth.register');

    }

       /**
     * Register user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();
        // dd($validated);
        // Retrieve a portion of the validated input data...        
        $validated = $request->safe()->except(['password_confirmation']);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if($request->segment("1")!="api"){
            return Redirect::back()->with(["sucess"=>"User successfully registered"]);
        }
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }
}
