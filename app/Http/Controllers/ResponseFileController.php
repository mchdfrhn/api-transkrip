<?php

namespace App\Http\Controllers;

use App\Models\ResponseFile;
use App\Services\ResponseFileServiceInterface;
use Illuminate\Http\Request;

class ResponseFileController extends Controller
{
    public function __construct(private ResponseFileServiceInterface $responseFileService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->responseFileService->getAllResponseFiles());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'response_id' => 'required|exists:responses,id',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240', // max 10MB
        ]);

        try {
            $responseFile = $this->responseFileService->createResponseFile($data);
            return response()->json([
                'status' => 'success',
                'message' => 'File uploaded successfully',
                'data' => $responseFile
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error uploading file',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ResponseFile $responseFile)
    {
        return response()->json($responseFile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResponseFile $responseFile)
    {
        $data = $request->validate([
            'response_id' => 'sometimes|required|exists:responses,id',
            'url' => 'sometimes|required|string',
        ]);

        $this->responseFileService->updateResponseFile($responseFile, $data);

        return response()->json($responseFile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResponseFile $responseFile)
    {
        $this->responseFileService->deleteResponseFile($responseFile);

        return response()->json(null, 204);
    }
}
