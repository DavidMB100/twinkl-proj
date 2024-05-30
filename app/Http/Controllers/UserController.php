<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
     /**
     * Store the user
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email_address' => 'required|email|unique:users',
            'type' => 'required|in:student,teacher,parent,private tutor',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors();
            return [
                "status" => 0,
                "response" => $message
            ];
        }

        $user = User::create($request->only(
            'first_name', 'last_name', 'email_address', 'type'
        ));

        // Send welcome email
        Mail::to($user->email_address)->send(new WelcomeEmail([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'type' => $user->type
        ]));

        return [
            "status" => 1,
            "data" => $user,
            "response" => "User created successfully"
        ];
    }
}
