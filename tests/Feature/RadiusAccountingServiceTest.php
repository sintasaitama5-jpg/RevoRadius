<?php

namespace Tests\Feature;

use App\Domains\Radius\Services\RadiusAccountingService;
use App\Models\RadiusAccounting;
use App\Models\RadiusSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RadiusAccountingServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_active_session_and_history_on_radius_start()
    {
        $service = new RadiusAccountingService();

        $data = [
            'acctsessionid' => '1234567890ABC',
            'username' => 'testuser',
            'nasipaddress' => '10.0.0.1',
            'framedipaddress' => '192.168.1.100',
            'callingstationid' => '00:11:22:33:44:55',
        ];

        $session = $service->startSession($data);

        // Check active session table
        $this->assertDatabaseHas('radius_sessions', [
            'acctsessionid' => '1234567890ABC',
            'username' => 'testuser',
            'framedipaddress' => '192.168.1.100',
        ]);

        // Check historical radacct table
        $this->assertDatabaseHas('radius_accounting', [
            'acctsessionid' => '1234567890ABC',
            'username' => 'testuser',
        ]);
    }

    public function test_it_removes_active_session_on_radius_stop()
    {
        $service = new RadiusAccountingService();

        $dataStart = [
            'acctsessionid' => 'STOPME123',
            'username' => 'testuser',
            'nasipaddress' => '10.0.0.1',
        ];
        $service->startSession($dataStart);

        $dataStop = [
            'acctsessionid' => 'STOPME123',
            'acctsessiontime' => 3600,
            'acctterminatecause' => 'User-Request',
            'acctinputoctets' => 1024,
            'acctoutputoctets' => 2048,
        ];
        $service->stopSession($dataStop);

        // Should no longer be in active sessions
        $this->assertDatabaseMissing('radius_sessions', [
            'acctsessionid' => 'STOPME123'
        ]);

        // History table should be updated with stop times
        $this->assertDatabaseHas('radius_accounting', [
            'acctsessionid' => 'STOPME123',
            'acctsessiontime' => 3600,
            'acctterminatecause' => 'User-Request',
            'acctinputoctets' => 1024,
            'acctoutputoctets' => 2048,
        ]);
    }
}