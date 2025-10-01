<?php

namespace App\Http\Controllers;

use App\Models\RequestFile;
use App\Providers\RequestFileServiceInterface;
use Illuminate\Http\Request;

class RequestFileController extends Controller
{
    public function __construct(private RequestFileServiceInterface $requestFileService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->requestFileService->getAllRequestFiles());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'request_id' => 'required|exists:requests,id',
            'url' => 'required|string',
        ]);

        $requestFile = $this->requestFileService->createRequestFile($data);

        return response()->json($requestFile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestFile $requestFile)
    {
        return response()->json($requestFile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestFile $requestFile)
    {
        $data = $request->validate([
            'request_id' => 'sometimes|required|exists:requests,id',
            'url' => 'sometimes|required|string',
        ]);

        $this->requestFileService->updateRequestFile($requestFile, $data);

        return response()->json($requestFile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestFile $requestFile)
    {
        $this->requestFileService->deleteRequestFile($requestFile);

        return response()->json(null, 204);
    }
}
