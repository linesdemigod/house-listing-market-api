<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  //user sign up
  public function register(Request $request)
  {
    $attr = $request->validate([
      'name' => 'required|string',
      'telephone' => 'required|string',
      'email' => 'required|unique:users,email',
      'password' => 'required|min:6|confirmed',
      'address' => 'required|string',
      // 'address' => 'required_if:role,agent|string',
    ]);

    // Hash password
    $attr['password'] = Hash::make($attr['password']);

    // Create user
    $user = User::create($attr);

    // Return user and token
    return response([
      'user' => $user,
      'token' => $user->createToken('secret')->plainTextToken,
    ], 200);
  }

  public function login(Request $request)
  {
    $attr = $request->validate([
      'email' => 'required|email',
      'password' => 'required|min:6',
    ]);

    //attempt login
    if (!Auth::attempt($attr)) {
      return response([
        "message" => "Invalid Credentials",
      ], 401);
    }
    $user = auth()->user();

    //return user and token
    return response([
      'user' => $user,
      'token' => $user->createToken('secret')->plainTextToken,
    ], 200);
  }

  public function user()
  {

    return response([
      "user" => auth()->user(),
    ], 200);
  }

  public function logout(Request $request)
  {

    //auth()->user()->tokens()->delete();
    $request->user()->tokens()->delete();

    return response([
      "message" => "Logout success",
    ], 200);
  }

}
