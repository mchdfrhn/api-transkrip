<?php

namespace App\Services;

use App\Models\ResponseFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ResponseFileService implements ResponseFileServiceInterface
{
    public function getAllResponseFiles(): Collection
    {
        return ResponseFile::all();
    }

    public function createResponseFile(array $data): ResponseFile
    {
        $file = $data['file'];
        $responseId = $data['response_id'];
        
        // Generate a unique filename
        $filename = Str::uuid() . '_' . $file->getClientOriginalName();
        
        // Store the file in S3
        $path = Storage::disk('s3')->putFileAs(
            'responses/' . $responseId,
            $file,
            $filename,
            'public'
        );
        
        // Get the full S3 URL of the uploaded file
        $url = config('app.aws_url') . '/' . $path;
        
        // Create and return the ResponseFile record
        return ResponseFile::create([
            'id' => Str::uuid(),
            'response_id' => $responseId,
            'url' => $url,
            'filename' => $filename,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);
    }

    public function getResponseFileById(string $id): ?ResponseFile
    {
        return ResponseFile::find($id);
    }

    public function updateResponseFile(ResponseFile $responseFile, array $data): bool
    {
        if (isset($data['file'])) {
            // Delete the old file from S3
            Storage::disk('s3')->delete($responseFile->path);
            
            $file = $data['file'];
            $filename = Str::uuid() . '_' . $file->getClientOriginalName();
            
            // Store the new file
            $path = Storage::disk('s3')->putFileAs(
                'responses/' . $responseFile->response_id,
                $file,
                $filename,
                'public'
            );
            
            $data['url'] = config('app.aws_url') . '/' . $path;
            $data['path'] = $path;
            $data['filename'] = $filename;
            $data['mime_type'] = $file->getMimeType();
            $data['size'] = $file->getSize();
        }
        
        return $responseFile->update($data);
    }

    public function deleteResponseFile(ResponseFile $responseFile): ?bool
    {
        // Delete the file from S3
        Storage::disk('s3')->delete($responseFile->path);
        
        return $responseFile->delete();
    }
}
