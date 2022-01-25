<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GithubController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        return view('searchGit');
    }

    public function searchByUsername(Request $request)
    {
        $username = $request->input('username');
        try{
            $response = Http::get('https://api.github.com/users/'.$username);
            return $response;
        }catch(Exception $err){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }
}
