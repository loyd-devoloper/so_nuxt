<?php

namespace App\Http\Controllers\API\School;

use App\Mail\Otp;
use Illuminate\Http\Request;
use App\Models\Qad\Curriculum;
use App\Traits\DocumentsTrait;
use Illuminate\Support\Carbon;
use App\Models\Qad\SchoolAccount;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Auth\VerificationCode;
use App\Models\School\ProgramOffered;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use DocumentsTrait;
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
            $check = VerificationCode::query()->where('email', $school->school_email_address)->where('user_id', $school->school_number)->orderBy('id', 'desc')->first();
            $code = rand(100000, 999999);
            if ($check) {

                if (!!$check->verified_at && $check->status == 1) {
                    $checkTime = Carbon::parse($check->verified_at)->diffInMinutes(Carbon::now());

                    if ($checkTime > 30) {

                        $check->update(['status' => 0, 'verified_at' => null, 'code' => $code]);
                        Mail::to('loyd.developer@gmail.com')->send(new Otp($code));
                        return response()->json(["message" => "Login Successful", "token" => Crypt::encryptString($check->id)], 200);
                    } else {

                        $token = $school->createToken('School')->plainTextToken;

                        return response()->json(["token" => $token, 'role' => 'School'], 201);
                    }
                } else {

                    $diffSeconds =  Carbon::parse($check->updated_at)->diffInSeconds(Carbon::now());


                    $fixMinus = (10 * 60) - $diffSeconds;

                    if ($fixMinus < 0) {

                        $check->update(['status' => 0, 'verified_at' => null, 'code' => $code]);
                    }
                    Mail::to('loyd.developer@gmail.com')->send(new Otp($code));
                    return response()->json(["message" => "Login Successful", "token" => Crypt::encryptString($check->id)], 200);
                }
            } else {


                $check = VerificationCode::query()
                    ->create([
                        'email' => $school->school_email_address,
                        'user_id' => $school->school_number,
                        'code' => $code,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                Mail::to('loyd.developer@gmail.com')->send(new Otp($code));
                return response()->json(["message" => "Login Successful", "token" => Crypt::encryptString($check->id)], 200);
            }
            // $token = $school->createToken('School')->plainTextToken;
            // return response()->json(['token' => $token, 'role' => 'School'], 201);
        }
        return response()->json(['errors' => [
            'school_number' => ['Wrong Credentials']
        ]], 422);
    }

    public function schoolInfo(Request $request): JsonResponse
    {
        $tokenId = explode('|', $request->bearerToken())[0];
        PersonalAccessToken::query()->where('id', $tokenId)->value('name');
        $user = $request->user()->load(['sdoInformation' => function ($query) {
            $query->select('id', 'sdo_name');
        }, 'programOffered' => function ($query) {
            $query->select('school_id', 'track', 'strand', 'specialization', 'id');
        }]);
        return response()->json([$user, PersonalAccessToken::query()->where('id', $tokenId)->value('name')], 201);
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
            "admin_suffix" => 'nullable|string',
            "admin_contact_number" => 'required',
            "admin_email_address" => 'required',
            "school_contact_number" => 'required',
            "school_email_address" => 'required',
            "school_name" => 'required',
            "school_address" => 'required',
            "password" => 'required',

            'sec_permit' => 'required|file|mimes:pdf',
            'shs_provisional_permit' => 'required|file|mimes:pdf',
            'mayors_permit' => 'required|file|mimes:pdf',
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
        //Add School Document Requirements
        /*Create Saving Folder*/
        $savingFolder = "school_docs" . "/" . $request->input('school_number');
        try {
            DB::transaction(function () use ($request, $savingFolder) {

                $school = SchoolAccount::query()->where('id', $request->user()->id)->first();

                $school->admin_first_name = $request->input('admin_first_name');
                $school->admin_middle_name = $request->input('admin_middle_name');
                $school->admin_last_name = $request->input('admin_last_name');
                $school->admin_suffix = $request->input('admin_suffix');
                $school->school_contact_number = $request->input('school_contact_number');
                $school->school_email_address = $request->input('school_email_address');
                $school->admin_contact_number = $request->input('admin_contact_number');
                $school->admin_email_address = $request->input('admin_email_address');
                $school->password = Hash::make($request->input('password'));
                $school->school_head_name = $request->input('school_head_name');
                $school->owner_name = $request->input('owner_name');
                $school->school_address = $request->input('school_address');
                $school->school_name = $request->input('school_name');
                $school->is_first_time_login = 0;
                $school->status = 'validating';
                $school->save();

                foreach (json_decode($request->input('program_offered'), true) as $key => $program) {

                    ProgramOffered::query()->create([
                        "school_id" => $school->id,
                        "track" => $program['track'],
                        "strand" => $program['strand'],
                        "specialization" => $program["specialization"],
                        "is_available" => 1,
                        "is_qad_verified" => 0,
                        "created_at" => Carbon::now(),
                    ]);
                }

                //Insert SEC Permit
                if ($request->hasFile("sec_permit")) {
                    $fileSaved = $this->storeFile($request->file('sec_permit'), $savingFolder);
                    $this->addDocument(
                        $school->id,
                        $documentName = "SEC Permit",
                        $path = $fileSaved,
                        $expirationDate = $request->input('sec_expiration_date')
                    );
                }
                //Insert SHS Provisional Permit
                if ($request->hasFile("shs_provisional_permit")) {
                    $fileSaved =  $this->storeFile($request->file('shs_provisional_permit'), $savingFolder);
                    $this->addDocument(
                        $school->id,
                        $documentName = "SHS Provisional Permit",
                        $path = $fileSaved,
                        $expirationDate = $request->input('shs_provisional_expiration_date')
                    );
                }
                //Insert Meyors Permit
                if ($request->hasFile("mayors_permit")) {
                    $fileSaved =  $this->storeFile($request->file('mayors_permit'), $savingFolder);
                    $this->addDocument(
                        $school->id,
                        $documentName = "Mayors Permit",
                        $path = $fileSaved,
                        $expirationDate = $request->input('shs_provisional_expiration_date')
                    );
                }
            });
            return response()->json(['success' => 'Submitted Successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => $e], 400);
        }
    }
}
