<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService implements UserServiceInterface
{
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function createUser(array $data): User
    {
        return User::create([
            'id' => Str::uuid(),
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'fullname' => $data['fullname'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);
    }

    public function updateUser(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function deleteUser(User $user): ?bool
    {
        return $user->delete();
    }
}