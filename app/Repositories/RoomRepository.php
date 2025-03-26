<?php

namespace App\Repositories;

use App\Models\Room;
use App\Repositories\contract\RoomRepositoryInterface;

class RoomRepository implements RoomRepositoryInterface
{
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Room::all();
    }

    public function create(array $data): Room
    {
        return Room::create($data);
    }

    public function update(array $data, int $id): bool
    {
        $room = Room::findOrFail($id);
        return $room->update($data);
    }

    public function delete(int $id): bool
    {
        $room = Room::findOrFail($id);
        return $room->delete();
    }
}
