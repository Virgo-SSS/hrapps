<?php

namespace App\Repository;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserProfileRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserRepository implements UserRepositoryInterface
{
    public function getUser(): Collection
    {
        $users = DB::table('users')
            ->join('user_profile', 'users.id', '=', 'user_profile.user_id')
            ->join('divisi', 'user_profile.divisi_id', '=', 'divisi.id')
            ->join('posisi', 'user_profile.posisi_id', '=', 'posisi.id')
            ->select('users.id','users.uuid','users.name','users.email', 'user_profile.cuti','user_profile.join_date', 'divisi.name as divisi_name', 'posisi.name as posisi_name')
            ->get();

        return $users;
    }

    public function create(array $request): void
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'uuid'      => $request['uuid'],
                'name'      => $request['name'],
                'password'  => bcrypt($request['password']),
                'email'     => $request['email'],
            ]);

            app(UserProfileRepositoryInterface::class)->store($user, $request);

            $role = Role::findById($request['role_id']);
            $user->assignRole($role->name);
        });

    }

    public function update(array $request, User $user): void
    {
        DB::transaction(function () use ($request, $user) {
            $user->update([
                'uuid'      => $request['uuid'],
                'name'      => $request['name'],
                'email'     => $request['email'],
            ]);

            app(UserProfileRepositoryInterface::class)->update($user, $request);

            $role = Role::findById($request['role_id']);
            $user->assignRole($role->name);
        });
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
