<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\LoginLinkMail;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use ApiResponser;

    public function sendLoginLink(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }


        $token = Hash::make(Str::random(60));
        //ایجاد url
        $url = URL::temporarySignedRoute(
            'auth.login',
            Carbon::now()->addMinutes(30),
            ['token' => $token, 'email' => $request->email , 'name' => $request->name]
        );

        // Send the email
        Mail::to($request->email)->send(new LoginLinkMail($url));

        return response()->json(['message' => 'Login link sent']);
    }


    public function login(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required',
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        if (!URL::hasValidSignature($request)) {
            return response()->json(['message' => 'Invalid or expired link'], 401);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        $token = $user->createToken('myApp')->plainTextToken;

        return response()->json(['message' => 'وظیفه با موفقیت اضافه شد.', 'user' => $user , 'token' => $token]);
        }

        // Create a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token]);
    }



    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'وظیفه با موفقیت اضافه شد.']);
    }


}
