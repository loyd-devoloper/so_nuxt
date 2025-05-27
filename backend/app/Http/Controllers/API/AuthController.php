<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function qadLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "password" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $apiData =  Http::withoutVerifying()->get('https://teahub.depedcalabarzon.ph/api/login-request/$2y$10$cLeGKQPtcL1mXbaAGp6NDeKml4EEN0468YrdSSLnjlMfZNxLgC/' . $request->email . '/' . $request->password);
        $result = json_decode($apiData);

        if ($result->status == "200" && strtolower($result->data->account_status) == "enabled") {
        } else {
            return response()->json(["errors" => ['email' => $result->remarks]], 422);
        }


        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($request->email)->plainTextToken;
    }
}
