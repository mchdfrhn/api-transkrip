<?php

namespace App\Http\Controllers;

use App\Providers\UserServiceInterface;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserServiceInterface $userService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->userService->getAllUsers());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:user|max:255',
            'fullname' => 'required|max:255',
            'email' => 'required|email|unique:user|max:255',
            'password' => 'required',
            'role' => 'required',
            'url_photo' => 'nullable|url',
        ]);

        $user = $this->userService->createUser($request->all());

        return response()->json($user, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|unique:user,username,' . $user->id . '|max:255',
            'fullname' => 'required|max:255',
            'email' => 'required|email|unique:user,email,' . $user->id . '|max:255',
            'role' => 'required',
            'url_photo' => 'nullable|url',
        ]);

        $this->userService->updateUser($user, $request->except('password'));

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);

        return response()->json(null, 204);
    }
}
