<?php

namespace App\Repository;

use App\Exceptions\CutiDateRequestedException;
use App\Exceptions\CutiRequestStillProcessingException;
use App\Interfaces\CutiRepositoryInterface;
use App\Interfaces\CutiRequestRepositoryInterface;
use App\Models\Cuti;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CutiRepository implements CutiRepositoryInterface
{
    public function getCuti(): Collection
    {
        return Cuti::with(['user', 'user.profile', 'user.profile.divisi', 'user.profile.posisi', 'cutiRequest'])
            ->where('user_id', auth()->user()->id)->get();
    }

    public function getPendingCuti(): Collection
    {
        $leave = [];
        Cuti::with(['cutiRequest'])->Pending()->get()
        ->each(function ($item) use(&$leave) {
              if($item->cutiRequest->head_of_division == auth()->user()->id && $item->cutiRequest->status_hod == config('cuti.status.pending')) {
                  $leave[] = $item;
              }

              if($item->cutiRequest->head_of_department == auth()->user()->id
                  && $item->cutiRequest->status_hodp == config('cuti.status.pending')
                  && $item->cutiRequest->status_hod != config('cuti.status.pending')) {
                  $leave[] = $item;
              }
        });

        return collect($leave);
    }

    public function store(array $request): void
    {
        DB::transaction(function () use ($request) {
            $this->checkLeave();
            $this->checkAvailableDate($request);

            $date = explode(' ', $request['date']);
            $cuti = auth()->user()->cuti()->create([
                'from' => $date[0],
                'to' => $date[2],
                'reason' => $request['reason'],
            ]);

            app(CutiRequestRepositoryInterface::class)->create($cuti, $request);
        });
    }

    public function update(array $request, Cuti $cuti): void
    {
        DB::transaction(function () use ($request, $cuti) {
            $cuti->update([
                'reason' => $request['reason'],
            ]);

            app(CutiRequestRepositoryInterface::class)->update($cuti, $request);
        });
    }

    public function delete(Cuti $cuti): void
    {
        $cuti->delete();
    }

    private function checkLeave(): void
    {
        $checkCuti = Cuti::where('user_id', auth()->user()->id)
            ->pending()
            ->exists();

        throw_if($checkCuti, new CutiRequestStillProcessingException());
    }

    private function checkAvailableDate(array $request): void
    {
        $checkCuti = Cuti::where('user_id', auth()->user()->id)
            ->Approved()
            ->get();

        $notAavailableDate = [];
        foreach ($checkCuti as $cuti) {
            $period = CarbonPeriod::create($cuti->from, $cuti->to);
            foreach($period as $date){
                $notAavailableDate[] = $date->format('d-m-Y');
            }
        }

        $date = explode(' ', $request['date']);
        $period2 = CarbonPeriod::create($date[0], $date[2]);
        $requestDate = [];
        foreach ($period2 as $date) {
            $requestDate[] = $date->format('d-m-Y');
        }

        $result = array_intersect($notAavailableDate, $requestDate);
        throw_if(!empty($result), new CutiDateRequestedException(implode(', ', $result)));
    }

    public function processStatus(Cuti $cuti, array $request): void
    {
        DB::transaction(function () use ($cuti, $request) {
            if($cuti->cutiRequest->head_of_division == auth()->user()->id && $cuti->cutiRequest->status_hod == config('cuti.status.pending')) {
                $this->head_of_division_action($cuti, $request);
            }

            if($cuti->cutiRequest->head_of_department == auth()->user()->id  && $cuti->cutiRequest->status_hodp == config('cuti.status.pending')
                && $cuti->cutiRequest->status_hod != config('cuti.status.pending')) {
                $this->head_of_department_action($cuti, $request);
                $cuti = $cuti->fresh();
                $this->commit($cuti);
            }
        });
    }

    private function head_of_division_action(Cuti $cuti, array $request): void
    {
        $cuti->cutiRequest()->update([
            'status_hod' => $request['status'] ? config('cuti.status.approved') : config('cuti.status.rejected'),
            'note_hod' => $request['note'] ?? '',
            'approved_hod_at' => Carbon::now(),
        ]);
    }

    private function head_of_department_action(Cuti $cuti, array $request): void
    {
        $cuti->cutiRequest()->update([
            'status_hodp' => $request['status'] ? config('cuti.status.approved') : config('cuti.status.rejected'),
            'note_hodp' => $request['note'] ?? '',
            'approved_hodp_at' => Carbon::now(),
        ]);
    }

    private function commit(Cuti $cuti): void
    {
        $hod = $cuti->cutiRequest->status_hod;
        $hodp = $cuti->cutiRequest->status_hodp;

        $status = config('cuti.status.rejected');

        if($hod == config('cuti.status.approved') && $hodp == config('cuti.status.approved')) {
            $status = config('cuti.status.approved');
        }

        $cuti->update([
            'status' => $status
        ]);
    }
}
