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
            'url' => 'required|string',
        ]);

        $responseFile = $this->responseFileService->createResponseFile($data);

        return response()->json($responseFile, 201);
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
