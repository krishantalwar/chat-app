<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Requests\LoginUserRequest;
use Redirect;

class LoginController extends Controller
{
    //

    public function index()
    {
        return view('auth.login');
    }

    /**
     * login user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();
        $creaitilas = $request->safe()->except('rembber');
        $remberme = $request->safe()->only('rembber');

        if (!$token = JWTAuth::attempt($creaitilas, $remberme = false)) {
            return $this->returnResponse(HTTP_STATUS_NOT_FOUND, true, "not found");
        }

        return $this->respondWithToken($token);
    }


    public function weblogin(LoginUserRequest $request)
    {

        $validated = $request->validated();
        $creaitilas = $request->safe()->except('rembber');
        $remberme = $request->safe()->only('rembber');

        if (Auth::attempt($creaitilas)) {
            return redirect()
                ->intended(route('dashboard'));
        }

        return Redirect::back();
    }

    /**
     * Logout user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return redirect()->route('home');
    }



    public function profile()
    {
        return response()->json(auth()->user());
    }
}