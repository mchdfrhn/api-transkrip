<?php

namespace App\Http\Controllers;

use App\Models\Request as RequestModel;
use App\Providers\RequestServiceInterface;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function __construct(private RequestServiceInterface $requestService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->requestService->getAllRequests());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:user,id',
            'type' => 'required|string',
            'queue' => 'required|integer',
            'request' => 'required|string',
        ]);

        $requestModel = $this->requestService->createRequest($data);

        return response()->json($requestModel, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestModel $request)
    {
        return response()->json($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $httpRequest, RequestModel $request)
    {
        $data = $httpRequest->validate([
            'user_id' => 'sometimes|required|exists:user,id',
            'type' => 'sometimes|required|string',
            'queue' => 'sometimes|required|integer',
            'request' => 'sometimes|required|string',
        ]);

        $this->requestService->updateRequest($request, $data);

        return response()->json($request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestModel $request)
    {
        $this->requestService->deleteRequest($request);

        return response()->json(null, 204);
    }
}
