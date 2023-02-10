<?php

namespace App\Repository;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{
    public function getUser(): Collection
    {
       return DB::table('users')
        ->join('user_profile', 'users.id', '=', 'user_profile.user_id')
        ->join('divisi', 'user_profile.divisi_id', '=', 'divisi.id')
        ->join('posisi', 'user_profile.posisi_id', '=', 'posisi.id')
        ->select('users.*', 'user_profile.*', 'divisi.name as divisi_name', 'posisi.name as posisi_name')
        ->get();
    }

    public function create(array $request): void
    {
        $user = User::create([
            'uuid'      => $request['uuid'],
            'name'      => $request['name'],
            'password'  => bcrypt($request['password']),
            'email'     => $request['email'],
        ]);

        app(UserProfileRepositoryInterface::class)->store($user, $request);
    }

    public function update(array $request, User $user): void
    {
        $user->update([
            'uuid'      => $request['uuid'],
            'name'      => $request['name'],
            'email'     => $request['email'],
        ]);

        app(UserProfileRepositoryInterface::class)->update($user, $request);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
