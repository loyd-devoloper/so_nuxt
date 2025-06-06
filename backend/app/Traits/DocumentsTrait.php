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
        $originalFileName = $file->getClientOriginalName();
        $timestamp = time(); // Get the current timestamp
        $extension = $file->getClientOriginalExtension(); // Get the file extension

        // Create a new filename with the original name and timestamp
        $newFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $extension;

        $file->storeAs($savingFolder, $newFileName);
        return $savingFolder . "/" . $newFileName; // Return Filename to use in saving to DB;

    }

    public function addDocument($school_id, $documentName, $path,$application_id = null, $expirationDate = null): void
    {
        Documents::query()->create([
            "school_id" => $school_id,
             "application_id" => $application_id,
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
