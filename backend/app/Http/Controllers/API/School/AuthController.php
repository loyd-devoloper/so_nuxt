<?php

namespace App\Http\Controllers\API\School;

use App\Http\Controllers\Controller;
use App\Models\Qad\Curriculum;
use App\Models\Qad\SchoolAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function schoolLogin(Request $request): JsonResponse
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
            return response()->json(['token' => $token, 'role' => 'School'], 201);
        }
        return response()->json(['errors' => [
            'school_number' => ['Wrong Credentials']
        ]], 422);
    }

    public function schoolInfo(Request $request): JsonResponse
    {
        $tokenId = explode('|', $request->bearerToken())[0];
        PersonalAccessToken::query()->where('id', $tokenId)->value('name');
        return response()->json([$request->user(), PersonalAccessToken::query()->where('id', $tokenId)->value('name')], 201);

    }


    public function latestCurriculum(): JsonResponse
    {
        $latestCurriculum = Curriculum::query()->with(['programs'])->latest()->first();
        return response()->json($latestCurriculum, 200);
    }

    public function firstTimeLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "admin_first_name" => 'required|string',
            "admin_middle_name" => 'nullable|string',
            "admin_last_name" => 'required|string',
            "suffix" => 'nullable|string',
            "contact_number" => 'nullable|string',
            "email_address" => 'nullable|email',
            "password" => 'required',
            "school_head_name" => 'nullable|string',
            "owner_name" => 'nullable|string',
            'sec_permit' => 'required|file|mimes:pdf',
            'shs_provisional_permit' => 'required|file|mimes:pdf',
            'mayors_permit' => 'required|file|mimes:pdf',
            'shs_provisional_expiration_date' => 'required|date',
            'program_offered.*.track' => 'required',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (empty(json_decode($request->program_offered, true))) {
                $validator->errors()->add('program_offered', 'Program Offered is required.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
                DB::transaction(function () use ($request) {
                        SchoolAccount::query()->where('id',$request->user()->id)->update([
                            "admin_first_name" => $request->input('admin_first_name'),
                            "admin_middle_name" => $request->input('admin_middle_name'),
                            "admin_last_name" => $request->input('admin_last_name'),
                            "suffix" => $request->input('suffix'),
                            "contact_number" => $request->input('contact_number'),
                            "email_address" => $request->input('email_address'),
                            "password" =>Hash::make($request->input('password')),
                            "school_head_name" => $request->input('school_head_name'),
                            "owner_name" =>$request->input('owner_name'),
                        ]);
                });
        } catch (\Exception $e) {
            return response()->json(["error" => $e], 400);
        }
    }
}
