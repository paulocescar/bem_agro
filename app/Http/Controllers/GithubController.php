<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\User;
use App\gitUsers;
use Auth;

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
        
        $data = gitUsers::where('user_id',Auth::User()->id)->get();
        return view('searchGit', compact('data'));
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

    public function searchByUsernameAdd(Request $request)
    {
        $username = $request->input('username');
        try{
            $gitUser = gitUsers::where('username',$username)->get();
            if($gitUser == '[]'){
                $response = Http::get('https://api.github.com/users/'.$username);
                return $response;
            }else{
                return response()->json(['error' => 1, 'message' => 'User found in your relations.'], 200);
            }
        }catch(Exception $err){
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }

    public function addGitUser(Request $request){
        try{
            $gitUser = new gitUsers();
            $gitUser->user_id = Auth::User()->id;
            $gitUser->username = $request->input('username');
            $gitUser->git_id = $request->input('git_id');
            $gitUser->git_url = $request->input('git_url');
            $gitUser->avatar_url = $request->input('avatar_url');
            $gitUser->repositories = $request->input('repositories');
            $gitUser->following = $request->input('following');
            $gitUser->followers = $request->input('followers');
            $gitUser->save();

            return response()->json(['error' => 0, 'message' => '!New gitUser added!'], 200);
        }catch(Exception $err){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
    public function removeGitUser(Request $request){
        try{
            $gitUser = gitUsers::where('git_id',$request->input('id'))->get();
            // return response()->json($gitUser);
            if($gitUser != '[]'){
                foreach($gitUser as $u){
                    $gitUserRemove = gitUsers::find($u->id);
                    $gitUserRemove->delete();
                }
            }
            return response()->json(['error' => 0, 'message' => '!New gitUser removed!'], 200);
        }catch(Exception $err){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
