<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\contract\UserRepositoryInterface;

class UserService
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function createUser(array $data): ?User
    {
        return $this->userRepository->create($data);
    }

    public function updateUser(array $data, int $id): int
    {
        return $this->userRepository->update($data, $id);
    }

    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }
}
