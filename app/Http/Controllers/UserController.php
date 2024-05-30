<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Rules\IpCheckRule;
use App\Rules\SpecialCharRule;

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
        $request['ip'] = $request->ip();
        
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', new SpecialCharRule],
            'last_name' => ['required', 'string', new SpecialCharRule],
            'email_address' => ['required', 'email', 'unique:users', new SpecialCharRule],
            'type' => ['required', 'in:student,teacher,parent,private tutor', new SpecialCharRule],
            'ip' => [new IpCheckRule],
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
