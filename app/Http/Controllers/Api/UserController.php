<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
//use Illuminate\Validation\Rule;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(!$request->user()->is_admin)
        {
            return response()->json([
                'message' => "Unauthorized Access!"
            ]);
        }

            if($request['email'])
            {
               $user = User::where('email', $request['email'])->first();
               if(!$user)
               {
                return response()->json([
                    'message' => "No User with Email Id:". $request['email']
                ]);
               }
               return $user;
            }
            return User::where('is_admin', '0')->paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email'=> 'required|email|unique:users,email',
            'password' => 'required|alpha_num|min:6|max:12',
        //    'is_admin' => ['required', Rule::in(['1', '0'])]
        ]);
        User::create([
            'name' => $request['name'],
            'email'=> $request['email'],
            'password' => $request['password'],
        ]);

        return response()->json([
            'message' => "User Registered succesfully"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
