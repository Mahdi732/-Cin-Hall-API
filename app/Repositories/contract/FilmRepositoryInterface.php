<?php

namespace App\Repositories\contract;

use App\Models\Film;

interface FilmRepositoryInterface
{
    public function getall();
    public function store(array $data);
    public function update(array $data, int $id);
    public function destroy(int $id);
}
