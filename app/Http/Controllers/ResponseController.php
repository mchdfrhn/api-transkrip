<?php

namespace App\Http\Controllers;

use App\Models\Response as ResponseModel;
use App\Providers\ResponseServiceInterface;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function __construct(private ResponseServiceInterface $responseService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->responseService->getAllResponses());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'request_id' => 'required|exists:requests,id',
            'response' => 'required|string',
        ]);

        $responseModel = $this->responseService->createResponse($data);

        return response()->json($responseModel, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ResponseModel $response)
    {
        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResponseModel $response)
    {
        $data = $request->validate([
            'request_id' => 'sometimes|required|exists:requests,id',
            'response' => 'sometimes|required|string',
        ]);

        $this->responseService->updateResponse($response, $data);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResponseModel $response)
    {
        $this->responseService->deleteResponse($response);

        return response()->json(null, 204);
    }
}
