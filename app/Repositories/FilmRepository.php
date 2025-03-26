<?php

namespace App\Repositories;

use App\Models\Film;
use Illuminate\Support\Facades\Auth;
use App\Repositories\contract\FilmRepositoryInterface;
class FilmRepository implements FilmRepositoryInterface
{

    public function getall() {
        return Film::with('session')->get();
    }

    public function store(array $data) : Film {
        return Auth::user()->films()->create($data);
    }

    public function update(array $data, int $id) : int {
        $film = Film::findOrFail($id);

        return $film->update($data);
    }

    public function destroy(int $id) : int {
        $film = Film::findOrFail($id);

        return $film->delete();
    }

}
