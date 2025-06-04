<?php

namespace App\Traits;

use App\Models\School\Documents;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
trait DocumentsTrait
{
    public function storeFile($requestFile, $savingFolder): string
    {
        if (!Storage::exists($savingFolder)) {
            Storage::makeDirectory($savingFolder);
            chmod(storage_path("app/private/$savingFolder"), 0755); // Set correct permissions
        }
        $file = $requestFile;
        $fileName = $file->getClientOriginalName();
        $file->storeAs($savingFolder, $fileName);
        return $savingFolder . "/" . $fileName; //Return Filename to use in saving to DB;
    }

    public function addDocument($school_id, $documentName, $path, $expirationDate): void
    {
        Documents::query()->create([
            "school_id" => $school_id,
            "document_name" => $documentName,
            "document_file" =>  $path,
            "document_expiry_date" => $expirationDate,
            "created_at" => Carbon::now(),
        ]);
    }
    public function loadFile($model, $columnName, $id): \Illuminate\Http\Response
    { // Retrieve the attachment
        $attachment = $model::find($id);
        $path = storage_path("app/private/" . $attachment->$columnName);
        $file = File::get($path);
        $type = File::mimeType($path);
        return Response::make($file, 200)->header('Content-Type', $type);
    }
}
