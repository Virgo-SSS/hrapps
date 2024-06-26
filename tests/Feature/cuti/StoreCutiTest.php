<?php

namespace Tests\Feature\cuti;

use App\Exceptions\CutiDateRequestedException;
use App\Exceptions\CutiRequestStillProcessingException;
use App\Interfaces\CutiRepositoryInterface;
use App\Models\Cuti;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreCutiTest extends baseCuti
{
    /**
     * @dataProvider DataFormCreateCuti
     */
    public function validation_form_request(string $field, string|int $value,string $errorMessage): void
    {
        $user = $this->createUserWithRoles('super admin');

        $request = $this->prepareRequest();
        $request[$field] = $value;

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertSessionHasErrors($field);
        $this->assertEquals($errorMessage, session()->get('errors')->first($field));
    }

    public function DataFormCreateCuti(): array
    {
        return [
            'store cuti field date is required' => [
                'date',' ', 'The date field is required.'
            ],
            'store cuti field date must be date range format' => [
                'date','asdfasdf', 'The date format is invalid.'
            ],
            'store cuti field date must be valid date range' => [
                'date',Carbon::now()->addDays(4)->format('Y-m-d') . ' - ' . Carbon::now()->format('Y-m-d'), 'Leave date is not valid'
            ],
            'store cuti field date from must be greater than today' => [
                'date', Carbon::now()->format('Y-m-d') . ' - ' . Carbon::now()->addDays(4)->format('Y-m-d'), 'Leave date must be greater than today'
            ],
            'store cuti field reason is required' => [
                'reason',' ', 'The reason field is required.'
            ],
            'store cuti field reason must_string' => [
                'reason', 123, 'The reason must be a string.'
            ],
            'store cuti field head_of_division is required' => [
                'head_of_division',' ', 'The head of division field is required.'
            ],
            'store cuti field head_of_division must be integer' => [
                'head_of_division', 'asdfasdf', 'The head of division must be an integer.'
            ],
            'store cuti field head_of_division must be exists' => [
                'head_of_division', 123, 'The selected head of division is invalid.'
            ],
            'store cuti field head_of_department is required' => [
                'head_of_department',' ', 'The head of department field is required.'
            ],
            'store cuti field head_of_department must be integer' => [
                'head_of_department', 'asdfasdf', 'The head of department must be an integer.'
            ],
            'store cuti field head_of_department must be exists' => [
                'head_of_department', 123, 'The selected head of department is invalid.'
            ],
        ];
    }

    /**
     * @dataProvider userCreate
     */
    public function test_user_store_cuti_success(string $role, ?string $permission = null): void
    {
        $user = $this->createUserWithRoles($role);
        if($permission) {
            $this->assignPermission($permission, $user);
        }

        $request = $this->prepareRequest();

        $response = $this->actingAs($user)->post(route('cuti.store'), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.index'));
        $response->assertSessionHas('toastr-success', 'Cuti created successfully.');

        $date = explode(' ', $request['date']);
        $this->assertDatabaseHas('cuti', [
            'user_id' => $user->id,
            'from' => $date[0],
            'to' => $date[2],
            'reason' => $request['reason'],
            'status' => config('cuti.status.pending'),
        ]);

        $this->assertDatabaseHas('cuti_request', [
            'cuti_id' => Cuti::where('user_id', $user->id)->first()->id,
            'head_of_division' => $request['head_of_division'],
            'status_hod' => config('cuti.status.pending'),
            'note_hod' => null,
            'head_of_department' => $request['head_of_department'],
            'status_hodp' => config('cuti.status.pending'),
            'note_hodp' => null,
        ]);
    }

    public function test_store_leave_requested_still_processing_got_the_right_exception(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        Cuti::factory()->create([
            'user_id' => $user->id,
            'status' => config('cuti.status.pending'),
        ]);

        $request = $this->prepareRequest();

        $this->expectException(CutiRequestStillProcessingException::class);
        $this->expectExceptionMessage('You already has a pending cuti request Please wait for the approval.');

        $repo = $this->app->make(CutiRepositoryInterface::class);
        $repo->store($request);
    }

    public function test_store_leave_requested_still_processing_redirect_back_with_the_right_message(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        Cuti::factory()->create([
            'user_id' => $user->id,
            'status' => config('cuti.status.pending'),
        ]);

        $request = $this->prepareRequest();

        $response = $this->from(route('cuti.create'))->post(route('cuti.store'), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.create'));
        $response->assertSessionHas('swal-warning', 'You already has a pending cuti request Please wait for the approval.');
    }

    public function test_store_leave_check_available_date_throw_right_exception(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $from = Carbon::now()->addDays(1)->format('Y-m-d');
        $to = Carbon::now()->addDays(4)->format('Y-m-d');

        Cuti::factory()->create([
            'user_id' => $user->id,
            'from' => $from,
            'to' => $to,
            'status' => config('cuti.status.approved'),
        ]);

        $request = $this->prepareRequest();
        $request['date'] = $from . ' - ' . $to;

        $date = CarbonPeriod::create($from, $to);

        $arrayDate = [];
        foreach($date as $d) {
            $arrayDate[] = $d->format('d-m-Y');
        }

        $this->expectException(CutiDateRequestedException::class);
        $this->expectExceptionMessage('You already has a cuti request on ' . implode(', ', $arrayDate) . '. You can only request cuti once in a period.');

        $repo = $this->app->make(CutiRepositoryInterface::class);
        $repo->store($request);
    }

    public function test_store_leave_check_available_date_redirect_back_with_the_right_message(): void
    {
        $user = $this->createUserWithRoles('super admin');
        $this->actingAs($user);

        $from = Carbon::now()->addDays(1)->format('Y-m-d');
        $to = Carbon::now()->addDays(4)->format('Y-m-d');

        Cuti::factory()->create([
            'user_id' => $user->id,
            'from' => $from,
            'to' => $to,
            'status' => config('cuti.status.approved'),
        ]);

        $request = $this->prepareRequest();
        $request['date'] = $from . ' - ' . $to;

        $date = CarbonPeriod::create($from, $to);

        $arrayDate = [];
        foreach($date as $d) {
            $arrayDate[] = $d->format('d-m-Y');
        }

        $response = $this->from(route('cuti.create'))->post(route('cuti.store'), $request);

        $response->assertStatus(302);
        $response->assertRedirect(route('cuti.create'));
        $response->assertSessionHas('swal-error', 'You already has a cuti request on ' . implode(', ', $arrayDate) . '. You can only request cuti once in a period.');
    }
}
