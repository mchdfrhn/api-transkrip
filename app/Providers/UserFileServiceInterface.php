<?php

namespace App\Providers;

use App\Models\UserFile;
use Illuminate\Database\Eloquent\Collection;

interface UserFileServiceInterface
{
    public function getAllUserFiles(): Collection;
    public function createUserFile(array $data): UserFile;
    public function getUserFileById(string $id): ?UserFile;
    public function updateUserFile(UserFile $userFile, array $data): bool;
    public function deleteUserFile(UserFile $userFile): ?bool;
}
