<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Qad\SdoAccount;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class QadSdoAccountController extends Controller
{

    public function index(request $request): \Illuminate\Http\JsonResponse
    {
        $limit = $request->limit ?? 10;
        $search = $request->q ?? '';
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $sdoAccounts = SdoAccount::query()->where(function ($query) use ($search) {
            $query->where('sdo_name', 'like', '%' . $search . '%')
                   ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('sds_name', 'like', '%' . $search . '%')
                    ->orWhere('asds_name', 'like', '%' . $search . '%')
                    ->orWhere('sdo_code', 'like', '%' . $search . '%');
        })->orderBy($sortColumn,$sortDirection)->paginate($limit);
        return response()->json($sdoAccounts,200);
    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'sdo_code'=>'required|unique:sdo_accounts,sdo_code',
            'sdo_name'=>'required',
            'email'=>'required|email|unique:sdo_accounts,email',
            'password'=>['required',Password::min(8)->mixedCase()->numbers()->symbols()],
            'type'=>'required',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }
        try {
            DB::Transaction(function () use ($request) {
                SdoAccount::query()->create([
                    'sdo_code'=>$request->sdo_code,
                    'sdo_name'=>$request->sdo_name,
                    'sds_name'=>$request->sds_name ? Crypt::encryptString($request->sds_name) : null,
                    'asds_name'=>$request->asds_name ? Crypt::encryptString($request->asds_name) : null,
                    'email'=>$request->email,
                    'password'=>Hash::make($request->password),
                    'type'=>$request->type,
                    'status'=>$request->status,
                ]);
            });

            return response()->json(['success'=>'Created Successfully'], 201);
        }catch (\Exception $e){
            return response()->json(["error" => $e], 400);
        }


    }

    public function edit(Request $request, $sdo_account_id): \Illuminate\Http\JsonResponse
    {
        $sdoAccount = SdoAccount::query()->where('id', $sdo_account_id)->first();
        if($sdoAccount){
            return response()->json($sdoAccount, 200);
        }else{
            return response()->json(['errors'=>'Not Found'], 404);
        }

    }

    public function update(Request $request, $sdo_account_id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sdo_code'=>'required',
            'sdo_name'=>'required',
            'type'=>'required',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }
        $sdoAccount = SdoAccount::query()->where('id', $sdo_account_id)->first();
        $sdoAccount->update([
            'sdo_code'=>$request->sdo_code,
            'sdo_name'=>$request->sdo_name,
            'sds_name'=>$request->sds_name ? Crypt::encryptString($request->sds_name) : null,
            'asds_name'=>$request->asds_name ? Crypt::encryptString($request->asds_name) : null,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'type'=>$request->type,
            'status'=>$request->status,
        ]);
        if($request->password)
        {
            $sdoAccount->update([
                'password'=>Hash::make($request->password),
            ]);
        }
        return response()->json(['success'=>'Updated Successfully'], 200);
    }
}
