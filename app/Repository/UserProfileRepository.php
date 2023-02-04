<?php

namespace App\Repository;

use App\Http\Requests\StoreUserRequest;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Collection;

class UserProfileRepository implements UserProfileRepositoryInterface
{
    public function getProfile(int $id): UserProfile
    {
        return UserProfile::where('user_id', $id)->first();
    }

    public function store(User $user, array $request): void
    {
        UserProfile::create([
            'user_id'             => $user->id,
            'divisi_id'           => $request['divisi_id'],
            'posisi_id'           => $request['posisi_id'],
            'bank'                => $request['bank'],
            'bank_account_number' => $request['bank_account_number'],
            'join_date'           => $request['join_date'],
            'cuti'                => $request['cuti'],
            'salary'              => $request['salary'],
        ]);
    }
}
