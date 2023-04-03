<?php

namespace App\Repository;

use App\Exceptions\CutiDateRequestedException;
use App\Exceptions\CutiRequestStillProcessingException;
use App\Interfaces\CutiRepositoryInterface;
use App\Interfaces\CutiRequestRepositoryInterface;
use App\Models\Cuti;
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
            $this->checkCuti();
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

    private function checkCuti(): void
    {
        $checkCuti = Cuti::where('user_id', auth()->user()->id)
            ->where('status', config('cuti.status.pending'))
            ->exists();

        throw_if($checkCuti, new CutiRequestStillProcessingException('You already has a pending cuti request Please wait for the approval.'));
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
        $message = 'You already has a cuti request on ' . implode(', ', $result) . '. You can only request cuti once in a period.';
        throw_if(!empty($result), new CutiDateRequestedException($message));
    }
}
