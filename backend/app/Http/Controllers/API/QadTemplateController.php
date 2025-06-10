<?php

namespace App\Http\Controllers\API;

use App\Models\Qad\Template;
use Illuminate\Http\Request;
use App\Traits\DocumentsTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class QadTemplateController extends Controller
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
        $templates = Template::query()->with(['userInfo'])
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            })
            ->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($templates, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file_path = $this->storeFile($request->file('file'), 'templates');
        Template::query()->create([
            'user_id' => $request->user()->id,
            'name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $file_path,
            'type' => $request->input('type')
        ]);
        return response()->json(['success' => 'Template Created Successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function downloadFile(string $template_id)
    {
        return $this->loadFile(
            Template::class,
            $columnName = "file_path",
            $id = $template_id
        );
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $template_id)
    {
        $template = Template::query()->where('id', $template_id)->first();
        $path = storage_path("app/private/" . $template->file_path);
        // Delete the file
        if (file_exists($path)) {
            unlink($path); // PHP native deletion
        }

        $template->delete();
        return response()->json(['success' => 'Template Delete Successfully'], 200);
    }
}
