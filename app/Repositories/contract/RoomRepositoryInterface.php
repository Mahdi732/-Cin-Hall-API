<?php
namespace App\Repositories\contract;

use App\Models\Room;

interface RoomRepositoryInterface
{
    public function getAll(): \Illuminate\Database\Eloquent\Collection;
    public function create(array $data): Room;
    public function update(array $data, int $id): bool;
    public function delete(int $id): bool;
}