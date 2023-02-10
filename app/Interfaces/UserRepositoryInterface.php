<?php

namespace App\Interfaces;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getUser(): Collection;

    public function create(array $request): void;

    public function update(array $request, User $user): void;

    public function delete(User $user): void;

}
