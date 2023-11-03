<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware(['guest']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.login');
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!$token = auth()->attempt($request->only('email', 'password'), $request -> remember)){
            return back() -> with('status', 'Invalid login details');
        }

        return $this->respondWithToken($token);
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
        $cookie = Cookie::make('edms', $token, 60);
        return redirect() -> route('dashboard')->withCookie($cookie);
    }
}
