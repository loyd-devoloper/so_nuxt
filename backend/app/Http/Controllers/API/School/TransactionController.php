<?php

namespace App\Http\Controllers\API\School;

use App\Models\School\SoApplication;
use App\Traits\DocumentsTrait;
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

                $attestation_path = $this->storeFile($request->file('attestation_file'), $savingFolder);
                $form_9_path = $this->storeFile($request->file('form_9_file'), $savingFolder);
                $this->addDocument($user->school_number,  'Form 9', $form_9_path, $application->id,);
                $this->addDocument($user->school_number, 'Attestation File', $attestation_path, $application->id);
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
                                "school_id" =>$user->school_number,
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

                    $this->addDocument($user->school_number, 'Student File', $students_path, $application->id);
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
        ->where(function($query) use ($search){
               $query->where('applied_strand', 'like', "%{$search}%")
                            ->orWhere('applied_track', 'like', "%{$search}%")
                            ->orWhere('applied_specialization', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%")
                            ->orWhereHas('curriculumInfo',function($query) use($search){
                               $query->where('school_year_start', 'like', '%' . $search . '%');
                                     $query->where('school_year_end', 'like', '%' . $search . '%');
                            });
        })->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($schoolSoApplications, 200);
    }

      public function indexStudents(Request $request,$application_id): \Illuminate\Http\JsonResponse
    {
        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $students = SoStudent::query()
        ->where('so_application_id',$application_id)
        ->where(function($query) use ($search){
               $query->where('lrn', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
        })->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($students, 200);
    }
}
