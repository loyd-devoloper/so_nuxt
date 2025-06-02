<?php

namespace App\Http\Controllers\API\School;

use App\Http\Controllers\Controller;
use App\Models\Qad\SchoolAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function schoolLogin(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'school_number' => 'required',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // Retrieve the school account by school number
        $school = SchoolAccount::query()->where('school_number', $request->school_number)->first();
        // Check if the school account exists and the password is correct
        if ($school && Hash::check($request->input('password'), $school->password)) {
            // Create a token for the school account
            $token = $school->createToken('School')->plainTextToken;
            return response()->json(['token' => $token,'role'=>'School'], 201);
        }
        return response()->json(['errors' => [
            'school_number'=>['Wrong Credentials']
        ]], 422);
    }
    public function schoolInfo(Request $request): \Illuminate\Http\JsonResponse
    {
            $tokenId = explode('|',$request->bearerToken())[0];
            PersonalAccessToken::query()->where('id', $tokenId)->value('name');
            return response()->json([$request->user(),   PersonalAccessToken::query()->where('id', $tokenId)->value('name')],201);

    }
}
