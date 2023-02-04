<?php

namespace App\Http\Controllers;

use App\Interfaces\DivisiRepositoryInterface;
use App\Interfaces\PosisiRepositoryInterface;
use App\Models\Posisi;
use App\Http\Requests\StorePosisiRequest;
use App\Http\Requests\UpdatePosisiRequest;
use Illuminate\Http\JsonResponse;

class PosisiController extends Controller
{
    private $Repository;
    private $divisiRepository;

    public function __construct(PosisiRepositoryInterface $Repository, DivisiRepositoryInterface $divisiRepository)
    {
        $this->Repository = $Repository;
        $this->divisiRepository = $divisiRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posisis = $this->Repository->getPosisi();
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('posisi.index', compact('posisis','divisis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePosisiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePosisiRequest $request)
    {
        $this->Repository->store($request);
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Posisi  $posisi
     * @return \Illuminate\Http\Response
     */
    public function show(Posisi $posisi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Posisi  $posisi
     * @return \Illuminate\Http\Response
     */
    public function edit(Posisi $posisi)
    {
        $divisis = $this->divisiRepository->getDivisiWithoutEagerLoading();
        return view('posisi.edit', compact('posisi','divisis'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePosisiRequest  $request
     * @param  \App\Models\Posisi  $posisi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePosisiRequest $request, Posisi $posisi)
    {
        $this->Repository->update($request, $posisi);
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Posisi  $posisi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Posisi $posisi)
    {
        $this->Repository->delete($posisi);
        return redirect()->route('posisi.index')->with('toastr-success', 'Posisi Successfully Deleted');
    }


    public function getPosisiByDivisi(int $divisi_id): JsonResponse
    {
        $posisis = $this->Repository->getPosisiByDivisi($divisi_id);
        return response()->json($posisis);
    }
}
