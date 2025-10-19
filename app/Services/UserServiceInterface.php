<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
{
    public function getAllUsers(): Collection;
    public function createUser(array $data): User;
    public function updateUser(User $user, array $data): bool;
    public function deleteUser(User $user): ?bool;
}
