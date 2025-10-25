<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Page;
use App\Models\UserModel;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_email' => 'required|email',
            'user_password' => 'required|string|min:6|',
        ]);

        //get  data of relative email 
        $userData = UserModel::where('email', $request->user_email)->first();

        //here we check if get data so we check password other wise return back with error like not email found
        if ($userData && Hash::check($request->user_password, $userData->password)) {
            //login succesfully
            $request->session()->regenerate(); //for prevention session attack 
            //now we start session and move to dashboard page
            session(['user_name' => $userData->name]);
            session(['user_email' => $userData->email]);
            session(['user_id' => $userData->id]);
            session(['user_phone'=>$userData->phone]);
            session(['user_created_at' => strtotime($userData->created_at)]);
            return redirect()->route('dashboard');
        } else {
            return redirect()->route('login.form')->with('failed', 'Invalid crendentials');
        }
    }

    public function register(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'user_name' => 'required|string|max:100|min:2',
            'user_email' => 'required|email|max:255|unique:users,email',
            'user_password' => 'required|string|min:6|',
        ]);

        // make object for model 
        $user = new UserModel();

        //get and save data into  model ovejct for save into db
        $user->name = $request->user_name;
        $user->email = $request->user_email;
        $user->password = Hash::make($request->user_password); //here we hash password   


        if ($user->save()) {
            return redirect()->route('login.form')->with('success', 'register successfully now please login');
        } else {
            return redirect()->route('register.form')->with('failed', 'register failed Something went wrong');
        }
    }

    public function logout(Request $request)
    {

        $request->session()->invalidate(); //clear session
        $request->session()->regenerateToken(); //prevent reuse

        //redirect to login page

        return redirect()->route('login.form')->with('success', 'logout successfully');
    }

    public function dashboard()
    {

        // Fetch all pages from the database
        // Get paginated pages (7 per page)
        $pages = Page::where('user_id', session('user_id'))->orderBy('created_at', 'desc')->paginate(7);

        // Return the list view with all pages
        return view('dashboard', compact('pages'));
    }
}
