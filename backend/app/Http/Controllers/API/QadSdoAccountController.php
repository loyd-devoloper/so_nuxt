<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Qad\SdoAccount;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class QadSdoAccountController extends Controller
{

    public function index(request $request)
    {
        $limit = $request->limit ?? 10;
        $sdoAccounts = SdoAccount::query()->paginate($limit);
        return response()->json($sdoAccounts,200);
    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'sdo_code'=>'required',
            'sdo_name'=>'required',
            'sds_name'=>'required',
            'asds_name'=>'required',
            'email'=>'required|email|unique:sdo_accounts,email',
            'password'=>['required',Password::min(8)->mixedCase()->numbers()->symbols()],
            'type'=>'required',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        SdoAccount::query()->create($request->all());
        return response()->json(['success'=>'Created Successfully'], 201);

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
            'sds_name'=>'required',
            'asds_name'=>'required',
            'email'=>'required|email|unique:sdo_accounts,email',
            'password'=>['required',Password::min(8)->mixedCase()->numbers()->symbols()],
            'type'=>'required',
            'status'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }
        $sdoAccount = SdoAccount::query()->where('id', $sdo_account_id)->first();
        $sdoAccount->update($request->except('password'));
        if($request->password)
        {
            $sdoAccount->update($request->only('password'));
        }
        return response()->json(['success'=>'Updated Successfully'], 200);
    }
}
