<?php

namespace App\Domains\Radius\Services;

use App\Models\RadiusUsersMap;
use App\Models\RadiusGroupsMap;
use Illuminate\Database\Eloquent\Model;

class RadiusUserService
{
    /**
     * Daftarkan member (Voucher atau PPPoE) ke radcheck agar bisa login via FreeRADIUS.
     * Secara otomatis mengkoneksikan user ini dengan group profilenya (radusergroup).
     *
     * @param Model $member (App\Models\Voucher atau App\Models\CustomerService)
     * @param Model $profile (App\Models\HotspotProfile atau App\Models\PppoeProfile)
     */
    public function registerUser(Model $member, string $username, string $password, Model $profile): RadiusUsersMap
    {
        // Temukan/buat mapping profil group terlebih dahulu (memastikan groupname sudah standar)
        $profileClass = get_class($profile);
        $groupname = strtolower(class_basename($profileClass)) . '_' . $profile->id;

        $map = RadiusUsersMap::updateOrCreate(
            [
                'member_type' => get_class($member),
                'member_id'   => $member->id,
            ],
            [
                'username'  => $username,
                'attribute' => 'Cleartext-Password',
                'op'        => ':=',
                'value'     => $password,
                'groupname' => $groupname,
                'is_active' => true,
            ]
        );

        return $map;
    }

    /**
     * Nonaktifkan atau block user
     * FreeRADIUS tidak punya native 'status=blocked' untuk radcheck.
     * Biasanya kita ganti Auth-Type := Reject, atau hilangkan row password
     * Di sini kita ganti Auth-Type := Reject dan tandai is_active = false
     */
    public function blockUser(Model $member): void
    {
        RadiusUsersMap::where('member_type', get_class($member))
            ->where('member_id', $member->id)
            ->update([
                'attribute' => 'Auth-Type',
                'op'        => ':=',
                'value'     => 'Reject',
                'is_active' => false,
            ]);
    }

    /**
     * Hapus member dari database RADIUS sepenuhnya
     */
    public function removeUser(Model $member): void
    {
        RadiusUsersMap::where('member_type', get_class($member))
            ->where('member_id', $member->id)
            ->delete();
    }
}
