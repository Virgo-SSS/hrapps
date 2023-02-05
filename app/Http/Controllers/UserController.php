<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Interfaces\PosisiRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    private $repository;
    private $divisiRepository;

    public function __construct(UserRepositoryInterface $repository, DivisiRepositoryInterface $divisiRepository)
    {
        $this->repository = $repository;
        $this->divisiRepository = $divisiRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->repository->getUser();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('users.create', compact('divisis' ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $this->repository->create($request);
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('users.edit', compact('user', 'divisis'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->repository->update($request, $user);
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->repository->delete($user);
        return redirect()->route('users.index')->with('toastr-success', 'User Successfully Deleted');
    }
}
