<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use App\Providers\UserFileServiceInterface;
use Illuminate\Http\Request;

class UserFileController extends Controller
{
    public function __construct(private UserFileServiceInterface $userFileService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->userFileService->getAllUserFiles());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:user,id',
            'url' => 'required|string',
        ]);

        $userFile = $this->userFileService->createUserFile($data);

        return response()->json($userFile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserFile $userFile)
    {
        return response()->json($userFile);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserFile $userFile)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|required|exists:user,id',
            'url' => 'sometimes|required|string',
        ]);

        $this->userFileService->updateUserFile($userFile, $data);

        return response()->json($userFile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserFile $userFile)
    {
        $this->userFileService->deleteUserFile($userFile);

        return response()->json(null, 204);
    }
}
