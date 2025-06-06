<?php

namespace App\Http\Controllers\API;

use App\Models\Auth\VerificationCode;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Qad\SchoolAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function qadLogin(Request $request): \Illuminate\Http\JsonResponse
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
              $user =  User::query()->updateOrCreate(['username'=>$result->data->username,'email'=>$result->data->email],[
                    "email" => $result->data->email,
                    "password" =>$result->data->password,
                    "fname" =>Crypt::encryptString($result->data->fname),
                    "lname" => Crypt::encryptString($result->data->lname),
                    "fd_code" => $result->data->fd_code,
                    "account_status" => $result->data->account_status,
                    "username" => $result->data->username,
                ]);

            $check = VerificationCode::query()->where('email', $result->data->email)->where('user_id', $user->id)->orderBy('id', 'desc')->first();
            $code = rand(100000, 999999);
            if ($check) {

                if (!!$check->verified_at && $check->status == 1) {
                    $checkTime =Carbon::parse($check->verified_at)->diffInMinutes( Carbon::now());

                    if ($checkTime > 30) {

                        $check->update(['status' => 0, 'verified_at' => null, 'code' => $code]);
                        return response()->json(["message" => "Login Successful","token" => Crypt::encryptString($check->id)],200);
                    } else {

                        $token = $user->createToken('Qad')->plainTextToken;

                        return response()->json(["token" => $token,'role'=>'Qad'],201);

                    }
                }
                else {

                    $diffSeconds =  Carbon::parse($check->updated_at)->diffInSeconds(Carbon::now());


                    $fixMinus = (10 * 60) - $diffSeconds;

                    if ($fixMinus < 0) {

                        $check->update(['status' => 0, 'verified_at' => null, 'code' => $code]);
                    }
                    return response()->json(["message" => "Login Successful","token" => Crypt::encryptString($check->id)],200);
                }
            } else {


                $check = VerificationCode::query()
                    ->create([
                    'email' => $result->data->email,
                    'user_id' => $user->id,
                    'code' => $code,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                ]);
                return response()->json(["message" => "Login Successful","token" => Crypt::encryptString($check->id)],200);

            }

        } else {
            return response()->json(["errors" => ['email' => [$result->remarks]]], 422);
        }


    }


    public function verifyOtp(Request $request): \Illuminate\Http\JsonResponse|array
    {


        $validator = Validator::make($request->all(), [
            "otp" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
       $otp =  VerificationCode::query()
            ->select('id','user_id','code')
            ->where('id', Crypt::decryptString($request->verification_id))
            ->first();
        if($otp->code == $request->otp)
        {

            if($request->input('role') === 'Qad')
            {

                $user = User::query()->where('id', $otp->user_id)->first();
                $token = $user->createToken('Qad')->plainTextToken;
                $otp->update([
                    'code' => null,
                    'verified_at'=>Carbon::now(),
                    'status'=>1
                ]);
            return response()->json(["token" => $token,"role"=>'Qad'],200);
            }
             if($request->input('role') === 'School')
            {
                $school = SchoolAccount::query()->where('school_number', $otp->user_id)->first();

            $token = $school->createToken('School')->plainTextToken;
            $otp->update([
                'code' => null,
                'verified_at'=>Carbon::now(),
                'status'=>1
            ]);
            return response()->json(["token" => $token,"role"=>'School'],200);
            }
        }
        return response()->json(['errors' => ['otp' => ['Invalid OTP']]], 401);
    }

    public function resendOtp(Request $request): \Illuminate\Http\JsonResponse
    {
        $code = rand(100000, 999999);
       VerificationCode::query()
                ->where('id', Crypt::decryptString($request->verification_id))
                ->update(['status' => 0, 'verified_at' => null, 'code' => $code]);
        return response()->json(["token" => Crypt::encryptString($request->verification_id)],200);
    }
    public function getOtp($verification_id): \Illuminate\Http\JsonResponse
    {
        try {
            $payload = Crypt::decryptString($verification_id);
            $verification = VerificationCode::query()->where('id',  $payload)->value('updated_at');

            return response()->json($verification,201);
        } catch (DecryptException $e) {
           return response()->json(["errors" => ['message' => $e->getMessage()]], 400);
        }
    }

    public function qadInfo(Request $request): \Illuminate\Http\JsonResponse
    {

            return response()->json($request->user());
    }

    public function logout(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message',
            'account logged out'
        ], 200);
    }
}
