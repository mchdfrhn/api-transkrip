<?php

namespace App\Providers;

use App\Models\UserFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class UserFileService implements UserFileServiceInterface
{
    public function getAllUserFiles(): Collection
    {
        return UserFile::all();
    }

    public function createUserFile(array $data): UserFile
    {
        return UserFile::create([
            'id' => Str::uuid(),
            'user_id' => $data['user_id'],
            'url' => $data['url'],
        ]);
    }

    public function getUserFileById(string $id): ?UserFile
    {
        return UserFile::find($id);
    }

    public function updateUserFile(UserFile $userFile, array $data): bool
    {
        return $userFile->update($data);
    }

    public function deleteUserFile(UserFile $userFile): ?bool
    {
        return $userFile->delete();
    }
}
