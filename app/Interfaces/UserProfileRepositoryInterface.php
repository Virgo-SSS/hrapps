<?php

namespace App\Interfaces;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Collection;

interface UserProfileRepositoryInterface
{
    public function getProfile(int $id): UserProfile;

    public function store(User $user, array $request): void;

    public function update(User $user, array $request): void;
}
