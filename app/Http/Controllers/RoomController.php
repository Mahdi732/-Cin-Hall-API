<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\contract\RoomRepositoryInterface;

class RoomController extends Controller
{
    protected RoomRepositoryInterface $roomRepository;

    public function __construct(RoomRepositoryInterface $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function index()
    {
        return response()->json($this->roomRepository->getAll(), 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'type' => 'required|in:normal,vip', 
            'capacity' => 'required|integer',
            'location' => 'nullable|string',
        ]);

        $user = Auth::user();
        abort_unless($user && $user->is_admin, 403, 'You are not allowed to create a room');

        $room = $this->roomRepository->create($fields);

        return response()->json([
            'message' => 'Room created successfully!',
            'room' => $room
        ], 201);
    }

    // Update an existing room
    public function update(Request $request, int $id)
    {
        $room = Room::findOrFail($id);

        $this->authorize('update', $room);

        $fields = $request->validate([
            'type' => 'sometimes|in:normal,vip',
            'capacity' => 'sometimes|integer',
            'location' => 'sometimes|nullable|string',
        ]);

        $this->roomRepository->update($fields, $id);

        return response()->json([
            'message' => 'Room updated successfully!'
        ], 200);
    }

    public function destroy(int $id)
    {
        $room = Room::findOrFail($id);

        $this->authorize('delete', $room);

        $this->roomRepository->destroy($id);

        return response()->json([
            'message' => 'Room deleted successfully!'
        ], 200);
    }
}
