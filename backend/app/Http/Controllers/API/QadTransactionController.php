<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School\SoApplication;

class QadTransactionController extends Controller
{
      public function index(Request $request)
    {
       $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $applications = SoApplication::query()
            ->with(['curriculumInfo','schoolInfo'=>function($query){
                $query->select('school_number','school_name','school_address','sdo_id')
                ->with('sdoInformation',function($query){
                    $query->select('sdo_name','id');
                });
            }])
            ->withCount('students')
        ->where(function ($query) use ($search) {
                $query->where('applied_strand', 'like', "%{$search}%")
                    ->orWhere('applied_track', 'like', "%{$search}%")
                    ->orWhere('applied_specialization', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereHas('curriculumInfo', function ($query) use ($search) {
                        $query->where('school_year_start', 'like', '%' . $search . '%');
                        $query->where('school_year_end', 'like', '%' . $search . '%');
                    })

                    ;
            })
        ->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($applications, 200);
    }
}
