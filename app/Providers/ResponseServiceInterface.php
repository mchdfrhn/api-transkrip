<?php

namespace App\Providers;

use App\Models\Response;
use Illuminate\Database\Eloquent\Collection;

interface ResponseServiceInterface
{
    public function getAllResponses(): Collection;
    public function createResponse(array $data): Response;
    public function getResponseById(string $id): ?Response;
    public function updateResponse(Response $response, array $data): bool;
    public function deleteResponse(Response $response): ?bool;
}
