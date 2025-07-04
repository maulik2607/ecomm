<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;



class LoginController extends Controller
{
  public function index()
  {
    $page = 'Admin/auth/login';
     $js = 'login';
    return view('Admin/layout', compact('page','js'));
  }

  public function autheticate(Request $request)
  {
    $credentials = $request->validate(
      [
        'email' => ['required', 'email'],
        'password' => ['required','min:8'],
      ],
      [
        'email.required' => 'Please enter your email address',
        'email.email' => 'Please enter a valid email address',
       'password.required' => 'Please enter your password',
    'password.min' => 'Your password must be at least 8 characters long',
      ]
    );
    $user = User::where('email', $request->email)->first();



    if ($user && Hash::check($request->password, $user->password)) {
        $roles = Role::where('name','!=','customer')->pluck('name')->toArray();
      
     
      if(isset($user->roles) && in_array(@$user->roles[0]->name,$roles)){

        Auth::login($user);
        if (!Auth::user()->is_active) {
          Auth::logout();
          return redirect()->back()->with('error','Access denied: No role assigned to your account. Please contact the administrator.');
        }
        return redirect()->intended('admin/dashboard')->with('success', 'Login successfully');;
      }
      else{
          return back()->with('error', 'Access denied: No role assigned to your account. Please contact the administrator.');
      }
    }
    return back()->with('error', 'Invalid creditial');
}


  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/admin');
  }
}
