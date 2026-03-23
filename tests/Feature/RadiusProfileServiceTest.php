<?php

namespace Tests\Feature;

use App\Domains\Radius\Services\RadiusProfileService;
use App\Models\RadiusGroupsMap;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DummyProfile extends Model
{
    protected $fillable = ['id', 'name'];
}

class RadiusProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_maps_profile_attributes_to_radius_groups_map()
    {
        $service = new RadiusProfileService();
        $profile = new DummyProfile(['name' => 'Paket Hemat 3 Jam']);
        $profile->id = 99; // Mock ID

        $attributes = [
            ['attribute' => 'Mikrotik-Rate-Limit', 'op' => '=', 'value' => '3M/3M', 'type' => 'reply'],
            ['attribute' => 'Max-Daily-Session', 'op' => ':=', 'value' => '10800', 'type' => 'check']
        ];

        $service->syncProfileAttributes($profile, $attributes);

        $this->assertDatabaseHas('radius_groups_maps', [
            'groupname' => 'dummyprofile_99',
            'attribute' => 'Mikrotik-Rate-Limit',
            'value' => '3M/3M',
            'type' => 'reply',
            'profile_type' => DummyProfile::class,
            'profile_id' => 99
        ]);

        $this->assertDatabaseHas('radius_groups_maps', [
            'groupname' => 'dummyprofile_99',
            'attribute' => 'Max-Daily-Session',
            'value' => '10800',
            'type' => 'check',
            'op' => ':=',
            'profile_type' => DummyProfile::class,
            'profile_id' => 99
        ]);
    }
}
