<?php

namespace App\Http\Controllers\API\School;

use App\Http\Controllers\Controller;
use App\Models\Qad\Announcement;
use App\Models\Qad\Template;
use App\Models\School\Documents;
use App\Traits\DocumentsTrait;
use Illuminate\Http\Request;

class ResourcesController extends Controller
{
    use DocumentsTrait;
    public function getTemplate(Request $request)
    {
        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $templates = Template::query()
            ->where('name', 'LIKE', '%' . $search . '%')
            ->where('type', 'school')
            ->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($templates, 200);
    }

    public function getMemo(Request $request)
    {
        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';

        $memo = Announcement::query()
            ->where('file', '!=', null)->orWhere('type', $search)->orderBy($sortColumn, $sortDirection)->paginate($limit);

        return response()->json($memo, 200);
    }
    public function getAttachment(Request $request)
    {

        $search = trim($request->q);
        $limit = $request->limit ?? 10;
        $sortColumn = $request->sort ?? 'id';
        $sortDirection = $request->d ?? 'asc';
        $attachments = Documents::query()
        ->select('document_name','application_id','id','created_at')
        ->where(function ($query) use ($search) {
            $query->where('document_name', 'LIKE', '%' . $search . '%');
        })->where('school_id', $request->user()->school_number)->orderBy($sortColumn, $sortDirection)->paginate($limit);
        return response()->json($attachments, 200);
    }

     public function downloadAttachment($attachment_id)
    {

         return $this->loadFile(
            Documents::class,
            $columnName = "document_file",
            $id = $attachment_id
        );
    }
}
