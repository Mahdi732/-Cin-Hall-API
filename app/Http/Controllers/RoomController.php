<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'name' => 'required|string',
            'capacity' => 'required|integer',
        ]);

        $user = Auth::user();
        abort_unless($user && $user->is_admin, 403, 'You are not allowed to create room');

        $room = $this->roomRepository->create($fields);

        return response()->json([
            'message' => 'Room created',
            'room' => $room
        ], 201);
    }
}
