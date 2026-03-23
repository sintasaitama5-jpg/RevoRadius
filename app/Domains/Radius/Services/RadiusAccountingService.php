<?php

namespace App\Domains\Radius\Services;

use App\Models\RadiusAccounting;
use App\Models\RadiusSession;
use App\Models\RadiusUsersMap;

class RadiusAccountingService
{
    /**
     * Handle incoming RADIUS Accounting Start from Mikrotik
     */
    public function startSession(array $data): RadiusSession
    {
        $userMap = RadiusUsersMap::where('username', $data['username'])->first();

        $session = RadiusSession::updateOrCreate(
            ['acctsessionid' => $data['acctsessionid']],
            [
                'username'            => $data['username'],
                'nasipaddress'        => $data['nasipaddress'],
                'framedipaddress'     => $data['framedipaddress'] ?? null,
                'callingstationid'    => $data['callingstationid'] ?? null,
                'acctstarttime'       => now(),
                'last_update_time'    => now(),
                'radius_users_map_id' => $userMap ? $userMap->id : null,
                'acctinputoctets'     => 0,
                'acctoutputoctets'    => 0,
            ]
        );

        // Create log historis (radacct)
        RadiusAccounting::create([
            'acctsessionid'      => $data['acctsessionid'],
            'acctuniqueid'       => md5($data['acctsessionid'] . $data['username']),
            'username'           => $data['username'],
            'nasipaddress'       => $data['nasipaddress'],
            'acctstarttime'      => now(),
            'callingstationid'   => $data['callingstationid'] ?? null,
            'framedipaddress'    => $data['framedipaddress'] ?? null,
            'user_map_id'        => $userMap ? $userMap->id : null,
        ]);

        return $session;
    }

    /**
     * Handle incoming RADIUS Accounting Interim-Update
     */
    public function interimUpdate(array $data): void
    {
        // Update session real-time
        RadiusSession::where('acctsessionid', $data['acctsessionid'])->update([
            'acctinputoctets'  => $data['acctinputoctets'] ?? 0,
            'acctoutputoctets' => $data['acctoutputoctets'] ?? 0,
            'last_update_time' => now(),
        ]);

        // Update historis (radacct)
        RadiusAccounting::where('acctsessionid', $data['acctsessionid'])->update([
            'acctupdatetime'   => now(),
            'acctinputoctets'  => $data['acctinputoctets'] ?? 0,
            'acctoutputoctets' => $data['acctoutputoctets'] ?? 0,
        ]);
    }

    /**
     * Handle incoming RADIUS Accounting Stop
     */
    public function stopSession(array $data): void
    {
        // Hapus dari active session
        RadiusSession::where('acctsessionid', $data['acctsessionid'])->delete();

        // Finalize di history
        RadiusAccounting::where('acctsessionid', $data['acctsessionid'])->update([
            'acctstoptime'       => now(),
            'acctsessiontime'    => $data['acctsessiontime'] ?? 0,
            'acctterminatecause' => $data['acctterminatecause'] ?? null,
            'acctinputoctets'    => $data['acctinputoctets'] ?? 0,
            'acctoutputoctets'   => $data['acctoutputoctets'] ?? 0,
        ]);
    }
}
