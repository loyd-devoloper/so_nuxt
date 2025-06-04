<?php

namespace App\Http\Controllers\API\School;

use App\Http\Controllers\Controller;
use App\Models\Qad\Curriculum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    public function activeCurricula()
    {
        $activeCurriculum = Curriculum::query()->where('is_open_for_so_application', true)->get();
        return response()->json($activeCurriculum,200);
    }

    public function storeApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attestation_file'          => 'required|mimes:pdf',
            'attachment_file'           => 'required|mimes:pdf',
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
    }
}
