<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SchoolNewAccount;
use App\Models\Qad\SchoolAccount;
use App\Models\Qad\SdoAccount;
use App\Models\School\Documents;
use App\Models\School\ProgramOffered;
use App\Traits\DocumentsTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\IOFactory;


class QadSchoolAccountController extends Controller
{
    use DocumentsTrait;

    public function schoolChangePassword(Request $request,$school_id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'password_confirmation' => 'required_with:password|same:password|required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        SchoolAccount::query()->where('id', $school_id)->update([
            'password'=>Hash::make($request->input('password'))
        ]);
        return response()->json(['success' => 'Password successfully changed'], 200);
    }

    public
    function viewAttachment($attachment_id): Response
    {
        return $this->loadFile(
            Documents::class,
            $columnName = "document_file",
            $id = $attachment_id
        );
    }

    /**
     * Display a listing of the resource.
     */
    public
    function index(Request $request)
    {

        $limit = $request->limit ?? 10;
        $search = $request->q ?? '';
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';

        $schoolAccounts = SchoolAccount::query()
            ->with(['sdoInformation'])
            ->where(function ($query) use ($search) {
                $query->where('school_name', 'like', '%' . $search . '%')
                    ->orWhere('school_number', 'like', '%' . $search . '%')
                    ->orWhere('school_address', 'like', '%' . $search . '%')
                    ->orWhere('school_head_name', 'like', '%' . $search . '%')
                    ->orWhere('admin_first_name', 'like', '%' . $search . '%')
                    ->orWhere('admin_last_name', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
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
    public
    function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'school_number' => 'required|unique:school_accounts,school_number',
            'school_email_address' => 'required|email|unique:school_accounts,school_email_address',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()],
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
                    'school_email_address' => $request->school_email_address,
                    'password' => Hash::make($request->password),
                    'school_name' => $request->school_name,
                    'school_address' => $request->school_address,
                    'sdo_id' => $request->sdo_id,
                ]);
            });
            return response()->json(['success' => 'Created Successfully'], 201);
        } catch (Exception $e) {
            return response()->json(["error" => $e], 400);
        }


    }

    public
    function storeBulk(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "excel_file" => "required|file|mimes:xlsx,xls",
        ]);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }
        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getSheet(0);
        $sheetData = $sheet?->toArray();
        if (!$sheetData) {
            return response()->json(["error" => "Sheet is empty or invalid format"], 400);
        }
        try {
            if (!$sheetData) {
                return response()->json(["error" => "Sheet is empty or invalid format"], 400);
            }

            $recordInserted = 0;
            $skippedEntries = [];

            foreach ($sheetData as $key => $row) {
                if ($key <= 3) continue; // Skip header rows
                if ($row[0] === null || $row[0] === '') continue;
                try {
                    // 1. Validate and clean school number
                    $school_number = preg_replace('/\s+/', '', $row[0]);
                    $school_name = strtoupper(trim($row[9]));
                    if (empty($school_number)) {
                        throw new Exception("School number cannot be empty");

                    }

                    if (!is_numeric($school_number)) {
                        throw new Exception("School number must be numeric");

                    }

                    $school_number = (int)$school_number;

                    // 2. Check for duplicates (uncomment if needed)
                    if (SchoolAccount::where('school_number', $school_number)->exists()) {
                        throw new Exception("Duplicate School ID '$school_number'");

                    }

                    // Check for duplicate School name
                    if (SchoolAccount::whereRaw("UPPER(TRIM(school_name)) = ?", [$school_name])->exists()) {

                        throw new Exception("Duplicate School name  '$school_name'");
                        continue;
                    }

                    // 3. Parse and validate date
                    $dateEstablished = null;
                    if (!empty($row[15])) {
                        try {
                            $dateEstablished = Carbon::createFromFormat('m/d/Y', $row[15])->format('Y-m-d');
                        } catch (Exception $e) {
                            // Try alternative format if first fails
                            $dateEstablished = Carbon::parse($row[15])->format('Y-m-d');
                        }
                    }

                    // 4. Find SDO
                    $sdo = SdoAccount::query()->where('id', $row[8])->first();
                    if (!$sdo) {
                        throw new Exception("SDO not found for code: " . $row[8]);
                    }

                    // 5. Create record
                    $password = uniqid();
                    $details = [
                        'subject' => 'School Account Credential',
                        'school_name' => strtoupper($row[9]),
                        'school_number' => $school_number,
                        'school_address' => $row[13] ?? null,
                        'password' => $password,
                    ];
                    Mail::to('loyd.developer@gmail.com')->queue(new SchoolNewAccount($details));
                    SchoolAccount::query()->create([
                        'school_number' => $school_number,
                        'sdo_id' => $sdo->id,
                        'school_name' => strtoupper($row[9]),
                        'school_address' => strtoupper($row[10]),
                        'school_head_name' => $row[13] ?? null,
                        'owner_name' => $row[14] ?? null,
                        'school_contact_number' => $row[12] ?? null,
                        'school_email_address' => $row[11] ?? null,
                        'date_established' => $dateEstablished,
                        'admin_first_name' => strtoupper($row[2]),
                        'admin_middle_name' => strtoupper($row[3]),
                        'admin_last_name' => strtoupper($row[4]),
                        'admin_email_address' => $row[11] ?? null,
                        'admin_contact_number' => $row[5] ?? null,
                        "password" => Hash::make($password),
                    ]);

                    $recordInserted++;

                } catch (Exception $e) {
                    $skippedEntries[] = "Row " . ($key + 1) . ": " . $e->getMessage();
                    continue; // This will continue to the next row
                }
            }

            return response()->json([
                "success" => "$recordInserted record(s) successfully registered",
                "skipped" => $skippedEntries
            ], 200);

        } catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
        return response()->json($file->getRealPath());
    }

    /**
     * Display the specified resource.
     */
    public
    function show(string $id): JsonResponse
    {
        try {
            $schoolAccount = SchoolAccount::query()->with(['sdoInformation' => function ($query) {
                $query->select('id', 'sdo_name');
            }, 'programOffered' => function ($query) {
                $query->select('school_id', 'track', 'strand', 'specialization');
            }, 'accountAttachments'])->where('id', $id)
                ->first();
            return response()->json($schoolAccount, 200);
        } catch (Exception $e) {
            return response()->json(["error" => $e], 400);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            "admin_first_name" => 'required|string',
            "admin_middle_name" => 'nullable|string',
            "admin_last_name" => 'required|string',
            "admin_suffix" => 'nullable|string',

            "admin_email_address" => 'required',
            "school_contact_number" => 'required',
            "school_email_address" => 'required',
            "school_name" => 'required',
            "school_address" => 'required',
            "school_head_name" => 'nullable|string',
            "owner_name" => 'nullable|string',
            'program_offered.*.track' => 'required',
        ]);
        $validator->after(function ($validator) use ($request) {
            if (empty($request->input('program_offered'))) {
                $validator->errors()->add('program_offered', 'Program Offered is required.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::transaction(function () use ($request, $id) {

                $school = SchoolAccount::query()->where('id', $id)->first();

                $school->admin_first_name = $request->input('admin_first_name');
                $school->admin_middle_name = $request->input('admin_middle_name');
                $school->admin_last_name = $request->input('admin_last_name');
                $school->admin_suffix = $request->input('admin_suffix');
                $school->school_contact_number = $request->input('school_contact_number');
                $school->school_email_address = $request->input('school_email_address');
                $school->admin_contact_number = $request->input('admin_contact_number');
                $school->admin_email_address = $request->input('admin_email_address');
                $school->school_head_name = $request->input('school_head_name');
                $school->owner_name = $request->input('owner_name');
                $school->school_address = $request->input('school_address');
                $school->school_name = $request->input('school_name');
                $school->is_first_time_login = 0;
                $school->status = 'approved';
                if (!!$request->input('password')) {
                    $school->password = Hash::make($request->input('password'));
                }
                $school->save();
                ProgramOffered::query()->where('school_id', $school->id)->delete();
                foreach ($request->input('program_offered') as $key => $program) {

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

            });
            return response()->json(['success' => 'Submitted Successfully'], 201);
        } catch (Exception $e) {
            return response()->json(["error" => $e], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function sdoList(): JsonResponse
    {
        return response()->json(SdoAccount::query()->select('sdo_name', 'id')->where('status', 'active')->orderBy('sdo_name', 'asc')->get(), 200);

    }
}
