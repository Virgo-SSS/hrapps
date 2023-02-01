<?php

namespace App\Repository;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getUser(): Collection
    {
        return User::with(['divisi'])->get();
    }

    public function create(StoreUserRequest $request): void
    {
        // TODO: Implement create() method.
    }

    public function update(UpdateUserRequest $request, User $user): void
    {

    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
