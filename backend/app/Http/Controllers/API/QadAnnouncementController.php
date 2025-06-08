<?php

namespace App\Http\Controllers\API;

use App\Models\Qad\Announcement;
use Illuminate\Http\Request;
use App\Traits\DocumentsTrait;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class QadAnnouncementController extends Controller
{
    use DocumentsTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $announcements = Announcement::with('userInfo')
        // ->where('expiration_date', '>=', Carbon::now())
        ->where(function($query) use($search){
            $query->where('type', 'like', "%{$search}%")
                            ->orWhere('expiration_date', 'like', "%{$search}%");
        })
        ->latest()
        ->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($announcements, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'              => 'required',
            'file'              => 'nullable|file',
            'content'           => 'required',
            'expiration_date'   => 'required|date',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user = $request->user();
        $savingFolder = "announcement_docs/" . Carbon::now()->format('Y-m-d_H-i-s');
        $file_path = $request->hasFile("file")
            ? $this->storeFile($request->file('file'), $savingFolder)
            : null;
        Announcement::query()
            ->create([
                'user_id'         => $user->id,
                'type'            => $request->type,
                'content'         => $request->content,
                'expiration_date' => $request->expiration_date,
                'file'            => $file_path, // include file path here, even if null
            ]);

        return response()->json(["success" => "Announcement successfully created"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $announcement_id)
    {
        $announcement = Announcement::find($announcement_id);
        if (!$announcement) {
            return response()->json(['message' => 'Announcement not found'], 404);
        }
        return response()->json($announcement, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $announcement_id)
    {
        $validator = Validator::make($request->all(), [
            'type'              => 'required',
            'file'              => 'nullable|file',
            'content'           => 'required',
            'expiration_date'   => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user = $request->user();
        $announcement = Announcement::find($announcement_id);
        $file_path = $request->hasFile("file")
            ?: null;

        if ($request->hasFile("file")) {
            $savingFolder = "announcement_docs/" . Carbon::now()->format('Y-m-d_H-i-s');
            $file_path = $this->storeFile($request->file, $savingFolder);
            $announcement->update([

                'file' => $file_path, // include file path here, even if null
            ]);
        }
        $announcement->update([
            'user_id'         => $user->id,
            'type'            => $request->type,
            'content'         => $request->content,
            'expiration_date' => $request->expiration_date,
            'file'            => $file_path, // include file path here, even if null
        ]);

        return response()->json(["success" => "Announcement Updated Successfully"], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
     public function viewFile(string $announcement_id)
    {
            return $this->loadFile(
            Announcement::class,
            $columnName = "file",
            $id = $announcement_id
        );
    }

}
