<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $authData = [
            'AUTH_ID' => $request->input('AUTH_ID'),
            'REFRESH_ID' => $request->input('REFRESH_ID'),
            'AUTH_EXPIRES' => $request->input('AUTH_EXPIRES'),
            'DOMAIN' => $request->input('DOMAIN'),
        ];
        return view('welcome', ['authData' => $authData]);
    }
}
