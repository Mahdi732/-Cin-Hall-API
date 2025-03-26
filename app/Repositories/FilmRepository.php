<?php

namespace App\Repositories;

use App\Models\Film;
use Illuminate\Support\Facades\Auth;
use App\Repositories\contract\FilmRepositoryInterface;
class FilmRepository implements FilmRepositoryInterface
{

    public function getAll()
    {
        return Film::all();
    }

    public function store(array $data)
    {
        return Film::create($data);
    }

    public function update(array $data, int $id)
    {
        $film = Film::findOrFail($id);
        $film->update($data);
        return $film;
    }

    public function destroy(int $id)
    {
        $film = Film::findOrFail($id);
        $film->delete();
    }
}
