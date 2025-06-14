<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Qad\Curriculum;
use App\Models\Qad\ProgramList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class QadCurriculumController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $curriculum = Curriculum::query()->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                if (Str::contains($search, '-')) {
                    [$startYear, $endYear] = array_map('trim', explode('-', $search));
                    $q->where('school_year_start', $startYear)
                        ->where('school_year_end', $endYear);
                } else {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('school_year_start', 'like', "%{$search}%")
                            ->orWhere('school_year_end', 'like', "%{$search}%")
                            ->orWhere('regional_director', 'like', "%{$search}%")
                            ->orWhere('is_open_for_so_application', 'like', "%{$search}%")
                            ->orWhere('assistant_regional_director', 'like', "%{$search}%");
                    });
                }
            });
        })->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($curriculum, 200);
    }
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "school_year_start"             => 'required',
            "school_year_end"               => 'required',
            "is_open_for_so_application"    => 'required',
            "regional_director"             => 'required',
            "assistant_regional_director"   => 'required',
            "open_date"                     => 'nullable',
            "closing_date"                  => 'nullable',
            "curriculum_id"                 => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

       Curriculum::query()->create($request->all());
        return response()->json(['success'=>'Created Successfully'], 201);
    }
    public function show($curriculum_id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Curriculum::query()->where('id',$curriculum_id)->first(), 200);
    }
    public function update(Request $request, $curriculum_id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "school_year_start"             => 'required',
            "school_year_end"               => 'required',
            "is_open_for_so_application"    => 'required',
            "regional_director"             => 'required',
            "assistant_regional_director"   => 'required',
            "open_date"                     => 'nullable',
            "closing_date"                  => 'nullable',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        Curriculum::query()->where('id', $curriculum_id)->update([
            'school_year_start'             => $request->school_year_start,
            'school_year_end'               => $request->school_year_end,
            'is_open_for_so_application'    => $request->is_open_for_so_application,
            "regional_director"             => $request->regional_director,
            "assistant_regional_director"   => $request->assistant_regional_director,
            "open_date"                     => $request->open_date,
            "closing_date"                  => $request->closing_date,
        ]);
        return response()->json(['success' => 'Curriculum update successfully'], 200);

    }

    public function storeProgram(Request $request,$curriculum_id): \Illuminate\Http\JsonResponse
    {
        $strand = $request->strand[0];
        $strandValue = $request->strand;
        if (is_null($strand['name']) && empty($strand['values'])) {
            $strandValue = [];
        }

        $validator = Validator::make($request->all(), [
            "track"             => 'required',
            "track_key"               => 'required',
            // "strand"               => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ProgramList::query()->create([
            'curriculum_id'=>$curriculum_id,
            'track'=>$request->input('track'),
            'track_key'=>$request->input('track_key'),
            'strand'=>$strandValue,
        ]);
        return response()->json(['success'=>'Created Successfully'], 201);
    }
    public function indexProgram(Request $request,$curriculum_id): \Illuminate\Http\JsonResponse
    {
        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';

        $programs = ProgramList::query()->where('curriculum_id',$curriculum_id)->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($programs, 200);
    }

    public function showProgram($curriculum_id,$program_id): \Illuminate\Http\JsonResponse
    {
       return response()->json(ProgramList::query()->where('curriculum_id',$curriculum_id)->where('id',$program_id)->first(), 200);
    }
    public function destroyProgram($program_id): \Illuminate\Http\JsonResponse
    {
        ProgramList::query()->where('id',$program_id)->delete();
       return response()->json(['success'=>'Program Deleted Successfully'], 200);
    }
    public function updateProgram(Request $request,$program_id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "track"             => 'required',
            "track_key"               => 'required',
            "strand"               => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ProgramList::query()
            ->where('id',$program_id)
            ->update([

            'track'=>$request->input('track'),
            'track_key'=>$request->input('track_key'),
            'strand'=>$request->strand,
        ]);
        return response()->json(['success'=>'Program Updated Successfully'], 201);
    }
}
