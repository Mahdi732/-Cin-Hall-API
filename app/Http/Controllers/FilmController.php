<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Repositories\contract\FilmRepositoryInterface;
use App\Services\FilmService;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FilmController extends Controller
{
    protected FilmRepositoryInterface $filmRepository;

    public function __construct(FilmRepositoryInterface $filmRepository) {
        $this->filmRepository = $filmRepository;
    }

    public function index()
    {
        return response()->json($this->filmRepository->getAll(), 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'required|string',
            'duration' => 'required',
            'minimum_age' => 'required|integer',
            'trailer_url' => 'required|string',
            'genre' => 'required|string'
        ]);

        $user = Auth::user();
        abort_unless($user && $user->is_admin, 403, "You can't create a film!");

        $film = $this->filmRepository->store($fields);

        return response()->json([
            "message" => "Film created!",
            "film" => $film
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $film = Film::findOrFail($id);

        $this->authorize('update', $film);

        $fields = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'image' => 'sometimes|string',
            'duration' => 'sometimes',
            'minimum_age' => 'sometimes|integer',
            'trailer_url' => 'sometimes|string',
            'genre' => 'sometimes|string'
        ]);

        $this->filmRepository->update($fields, $id);

        return response()->json(["message" => "Film updated!"], 200);
    }

    public function destroy(int $id)
    {
        $film = Film::findOrFail($id);

        $this->authorize('delete', $film);

        $this->filmRepository->destroy($id);

        return response()->json(['message' => 'Film deleted!'], 200);
    }

}
