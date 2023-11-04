<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware(['guest']);
    }
 
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this -> validate($request, [
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        User::create([
            'name' => $request -> name,
            'username' => $request -> username,
            'email' => $request -> email,
            'password' => Hash::make($request -> password)
        ]);


        auth()->attempt($request->only('email','password'));

        if(!$token = auth()->attempt($request->only('email', 'password'))){
            return back() -> with('status', 'Error: Try again later');
        }
        return $this->respondWithToken($token);
     }

    /**
     * Display the specified resource.
     */
    public function index()
    {
        return view('auth.register');
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
