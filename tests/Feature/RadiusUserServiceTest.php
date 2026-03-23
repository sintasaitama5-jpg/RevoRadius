<?php

namespace Tests\Feature;

use App\Domains\Radius\Services\RadiusUserService;
use App\Models\RadiusUsersMap;
use App\Models\RadiusGroupsMap;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DummyVoucher extends Model
{
    protected $fillable = ['id', 'username'];
}

class DummyHotspotProfile extends Model
{
    protected $fillable = ['id', 'name'];
}

class RadiusUserServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_registers_voucher_to_radius_users_map_with_correct_groupname()
    {
        $service = new RadiusUserService();

        $voucher = new DummyVoucher(['username' => 'user001']);
        $voucher->id = 101;

        $profile = new DummyHotspotProfile(['name' => '1 Jam']);
        $profile->id = 202;

        $service->registerUser($voucher, 'user001', 'password123', $profile);

        $this->assertDatabaseHas('radius_users_maps', [
            'username' => 'user001',
            'attribute' => 'Cleartext-Password',
            'op' => ':=',
            'value' => 'password123',
            'groupname' => 'dummyhotspotprofile_202', // Sesuai format
            'member_type' => DummyVoucher::class,
            'member_id' => 101,
            'is_active' => true
        ]);
    }
}