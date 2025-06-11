<?php

namespace App\Http\Controllers\API\School;

use App\Models\School\SoApplication;
use App\Traits\DocumentsTrait;
use Exception;
use Illuminate\Http\Request;
use App\Models\Qad\Curriculum;
use Illuminate\Support\Carbon;
use App\Models\School\SoStudent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    use DocumentsTrait;
    public function activeCurricula()
    {
        $activeCurriculum = Curriculum::query()->where('is_open_for_so_application', true)->get();
        return response()->json($activeCurriculum, 200);
    }

    public function storeApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attestation_file'          => 'required|mimes:pdf',
            'form_9_file'           => 'required|mimes:pdf',
            'students_file'             => 'nullable|mimes:xls,xlsx,csv',
            "applied_track"             => 'required|string',
            "applied_strand"            => 'required|string',
            "applied_specialization"    => 'nullable',
            "graduation_date"           => 'required',
            "curriculum_id"             => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([

                'errors' => $validator->errors(),
            ], 442);
        }
        $user = $request->user();
        if ($user->status !== 'approved') { //check if school account is approved
            return response()->json(["message" => "Account has not been validated yet"], 400);
        }


        $savingFolder = "school_docs" . "/" . $user->school_number . "/" . "transaction" . "/" . Carbon::now()->format('Y-m-d_H-i-s');


        try {




            DB::transaction(function () use ($user, $savingFolder, $request) {
                $application = SoApplication::query()
                    ->create([
                        "curriculum_id" => $request->curriculum_id,
                        "school_id" => $user->school_number,
                        "graduation_date"   => $request->graduation_date,
                        "date_granted"   => null,
                        "applied_track" => (substr($request->applied_track, 0, 3) == "TVL" || substr($request->applied_track, 0, 3) == "tvl") ? "Technical Vocational" : $request->applied_track,
                        "applied_strand" => $request->applied_strand,
                        "applied_specialization" => $request->applied_specialization,
                        "status" => "pending",
                        "created_at" => Carbon::now(),
                    ]);


                $form_9_path = $this->storeFile($request->file('form_9_file'), $savingFolder);
                $this->addDocument($user->school_number,  $request->file('form_9_file')->getClientOriginalName(), $form_9_path, $application->id,);
                $attestation_path = $this->storeFile($request->file('attestation_file'), $savingFolder);
                $this->addDocument($user->school_number, $request->file('attestation_file')->getClientOriginalName(), $attestation_path, $application->id);
                // Insert Student to Table
                if ($request->students_file) {
                    $spreadsheet = IOFactory::load($request->students_file);
                    $sheetData = $spreadsheet->getSheet(0)->toArray();
                    foreach ($sheetData as $key => $row) {
                        if ($key < 2) continue; // Skip row 1 - 2,
                        if (empty($row[1])) {
                            break;
                        } else {
                            SoStudent::query()->create([
                                "school_id" => $user->school_number,
                                "so_application_id" => $application->id,
                                "curriculum_id" => $request->curriculum_id,
                                "first_name" => $row[1],
                                "middle_name" => $row[2],
                                "last_name" => $row[3],
                                "suffix" => $row[4],
                                "lrn" => $row[5]
                            ]);
                        }
                    }
                    $students_path = $request->hasFile("students_file")
                        ? $this->storeFile($request->file('students_file'), $savingFolder)
                        : null;

                    $this->addDocument($user->school_number, $request->file('students_file')->getClientOriginalName(), $students_path, $application->id);
                }
                // $this->saveApplicationLogs(auth('sanctum')->user()->id, $application->id, 'Create', 'Create new SO Application', $application);
                return response()->json(["message" => "Application Successfully Submitted"], 200);
            });
            return response()->json(["success" => "Application Successfully Submitted"], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e], 400);
        }
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $schoolSoApplications = SoApplication::query()
            ->with('curriculumInfo')
            ->withCount('students')
            ->where(function ($query) use ($search) {
                $query->where('applied_strand', 'like', "%{$search}%")
                    ->orWhere('applied_track', 'like', "%{$search}%")
                    ->orWhere('applied_specialization', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('curriculumInfo', function ($query) use ($search) {
                        $query->where('school_year_start', 'like', '%' . $search . '%');
                        $query->where('school_year_end', 'like', '%' . $search . '%');
                    });
            })->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($schoolSoApplications, 200);
    }

    public function indexStudents(Request $request, $application_id): \Illuminate\Http\JsonResponse
    {
        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $students = SoStudent::query()
            ->where('so_application_id', $application_id)
            ->where(function ($query) use ($search) {
                $query->where('lrn', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($students, 200);
    }

    public function storeStudent(Request $request, $application_id)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",

            "last_name" => "required",
            "lrn" => "required",

        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $soApplication = SoApplication::query()->where('id', $application_id)->first();
        SoStudent::query()
            ->create([
                "school_id" => $request->user()->school_number,
                "so_application_id" => $application_id,
                "curriculum_id" => $soApplication->curriculum_id,
                "first_name"    => $request->input('first_name'),
                "middle_name"   => $request->input('middle_name'),
                "last_name"     => $request->input('last_name'),
                "suffix"        => $request->input('suffix'),
                "lrn"           => $request->input('lrn'),
            ]);
        return response()->json(["success" => "Student Created Successfully"], 201);
    }

    public function showStudent($student_id)
    {
        return response()->json(SoStudent::query()->where('id', $student_id)->first(), 200);
    }
    public function updateStudent(Request $request, $student_id)
    {
        $validator = Validator::make($request->all(), [
            "first_name" => "required",

            "last_name" => "required",
            "lrn" => "required",

        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        SoStudent::query()
            ->where('id', $student_id)
            ->update([

                "first_name"    => $request->input('first_name'),
                "middle_name"   => $request->input('middle_name'),
                "last_name"     => $request->input('last_name'),
                "suffix"        => $request->input('suffix'),
                "lrn"           => $request->input('lrn'),
            ]);
        return response()->json(["success" => "Student Updated Successfully"], 200);
    }

    public function storeStudentBulk(Request $request, $application_id)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx,csv',
        ]);
        try {
            $user = $request->user();


            $folder = "school_docs/{$user->school_number}/transaction/" . now()->format('Y-m-d_H-i-s');
            $file_path = $this->storeFile($request->file('excel_file'), $folder);
            if (!$file_path) {
                return response()->json(["message" => "Something went wrong saving the file."], 400);
            }
            $spreadsheet = IOFactory::load(storage_path('app/private/' . $file_path));
            $rows = $spreadsheet->getSheet(0)->toArray();
            $application = SoApplication::query()->where('id', $application_id)->first();

            $recordInserted = 0;
            $skippedEntries = [];
            foreach ($rows as $index => $row) {
                if ($index < 2) continue; // Skip header rows
                if (empty($row[1])) break;
                $lrn = trim($row[5]);

                try {
                    if (empty($lrn)) {

                        throw new Exception("Row " . ($index + 1) . ": LRN is empty.");
                    }
                    if (SoStudent::query()->where('so_application_id', $application_id)->where('lrn', $lrn)->exists()) {

                        throw new Exception("Row " . ($index + 1) . ": Duplicate student with LRN '$lrn'");
                    }
                    SoStudent::query()->create([
                        "school_id"         => $user->school_number,
                        "so_application_id" => $application_id,
                        "curriculum_id"     => $application->curriculum_id,
                        "first_name"        => trim($row[1]),
                        "middle_name"       => trim($row[2] ?? ''),
                        "last_name"         => trim($row[3]),
                        "suffix"            => trim($row[4] ?? ''),
                        "lrn"               => $lrn,
                    ]);
                    $recordInserted++;
                } catch (\Exception $e) {
                    $skippedEntries[] =  $e->getMessage();
                    continue; // This will continue to the next row
                }
            }
            // $this->saveDocument($school->id, $application_id, 'Student File', $file_path);


            $this->addDocument($user->school_number, $request->file('excel_file')->getClientOriginalName(), $file_path, $application->id);
            return response()->json([
                "success" => "$recordInserted record(s) successfully registered",
                "skipped" => $skippedEntries
            ], 200);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }
}
