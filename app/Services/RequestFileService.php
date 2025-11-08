<?php

namespace App\Services;

use App\Models\RequestFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class RequestFileService implements RequestFileServiceInterface
{
    public function getAllRequestFiles(): Collection
    {
        return RequestFile::all();
    }

    public function createRequestFile(array $data): RequestFile
    {
        $file = $data['file'];
        $requestId = $data['request_id'];
        
        // Generate a unique filename
        $filename = Str::uuid() . '_' . $file->getClientOriginalName();
        
        // Store the file in S3
        $path = 'requests/' . $requestId . '/' . $filename;
        Storage::disk('s3')->putFileAs(
            'requests/' . $requestId,
            $file,
            $filename,
            'public'
        );
        
        // Get the URL of the uploaded file using the AWS_URL from config
        $url = rtrim(config('filesystems.disks.s3.url'), '/') . '/' . $path;
        
        // Create and return the RequestFile record
        return RequestFile::create([
            'id' => Str::uuid(),
            'request_id' => $requestId,
            'url' => $url,
            'filename' => $filename,
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);
    }

    public function getRequestFileById(string $id): ?RequestFile
    {
        return RequestFile::find($id);
    }

    public function updateRequestFile(RequestFile $requestFile, array $data): bool
    {
        if (isset($data['file'])) {
            // Delete the old file from S3
            Storage::disk('s3')->delete($requestFile->path);
            
            $file = $data['file'];
            $filename = Str::uuid() . '_' . $file->getClientOriginalName();
            
            // Store the new file
            $path = 'requests/' . $requestFile->request_id . '/' . $filename;
            Storage::disk('s3')->putFileAs(
                'requests/' . $requestFile->request_id,
                $file,
                $filename,
                'public'
            );
            
            // Generate the new URL
            $url = rtrim(config('filesystems.disks.s3.url'), '/') . '/' . $path;
            
            $data['url'] = $url;
            $data['path'] = $path;
            $data['filename'] = $filename;
            $data['mime_type'] = $file->getMimeType();
            $data['size'] = $file->getSize();
        }
        
        return $requestFile->update($data);
    }

    public function deleteRequestFile(RequestFile $requestFile): ?bool
    {
        // Delete the file from S3
        if ($requestFile->path) {
            Storage::disk('s3')->delete($requestFile->path);
        }
        
        return $requestFile->delete();
    }
}
