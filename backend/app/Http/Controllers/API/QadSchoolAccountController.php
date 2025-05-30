<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Qad\SchoolAccount;
use App\Models\Qad\SdoAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;

class QadSchoolAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $limit = $request->limit ?? 10;
        $search = $request->q ?? '';
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';

        $schoolAccounts = SchoolAccount::query()
            ->with(['sdoInformation'])
            ->where(function ($query) use ($search) {
            $query->where('school_name','like','%'.$search.'%')
                ->orWhere('school_number','like','%'.$search.'%')
                ->orWhere('school_address','like','%'.$search.'%')
                ->orWhere('school_head_name','like','%'.$search.'%')
                ->orWhere('admin_first_name','like','%'.$search.'%')
                ->orWhere('admin_last_name','like','%'.$search.'%')
                ->orWhere('admin_email_address','like','%'.$search.'%')
                ->orWhere('owner_name','like','%'.$search.'%')
                ->orWhereHas('sdoInformation', function ($query) use ($search) {
                    $query->where('sdo_name', 'like', '%' . $search . '%');
                });
        })->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($schoolAccounts, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school_number' => 'required|unique:school_accounts,school_number|min:11|max:11',
            'school_email_address' => 'required|email|unique:school_accounts,school_email_address',
            'password'=>['required',Password::min(8)->mixedCase()->numbers()->symbols()],
            'school_name' => 'required|unique:school_accounts,school_name',
            'school_address' => 'required',
            'sdo_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(),], 422);
        }
        try {
           DB::Transaction(function () use ($request) {
               $store = SchoolAccount::query()->create([
                   'school_number' => $request->school_number,
                   'school_email_address' =>  $request->school_email_address,
                   'password'=> Hash::make($request->password),
                   'school_name' =>$request->school_name,
                   'school_address' =>$request->school_address,
                   'sdo_id' => $request->sdo_id,
               ]);
           });
            return response()->json(['success'=>'Created Successfully'], 201);
        }catch (\Exception $e){
            return response()->json(["error" => $e], 400);
        }


    }
    public function storeBulk(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "excel_file" => "required|file|mimes:xlsx,xls",
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 422);
        }
        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getSheet(0);
        $sheetData = $sheet?->toArray();
        if (!$sheetData) {
            return response()->json(["error" => "Sheet is empty or invalid format"], 400);
        }
        return response()->json($file->getRealPath());
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id): \Illuminate\Http\JsonResponse
    {
        try {
            $schoolAccount = SchoolAccount::query()->where('id',$id)->first();
            return response()->json([$schoolAccount], 200);
        }catch (\Exception $e){
            return response()->json(["error" => $e], 400);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'password' => 'nullable',
            'program_offered' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $schoolAccount = SchoolAccount::query()->where('id',$id)->first();
        $field = $validator->validated();
        try {
            DB::Transaction(function () use ($request,$schoolAccount,$field) {
                $schoolAccount->update($field);
            });
        }catch (\Exception $e){
            return response()->json(["error" => $e], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function sdoList(): \Illuminate\Http\JsonResponse
    {
       return response()->json( SdoAccount::query()->select('sdo_name','id')->where('status','active')->orderBy('sdo_name','asc')->get(),200);

    }
}
