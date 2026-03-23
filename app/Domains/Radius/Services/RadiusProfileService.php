<?php

namespace App\Domains\Radius\Services;

use App\Models\RadiusGroupsMap;
use Illuminate\Database\Eloquent\Model;

class RadiusProfileService
{
    /**
     * Map profile Hotspot/PPPoE ke radgroupcheck & radgroupreply
     * @param Model $profile (Bisa HotspotProfile atau PppoeProfile)
     * @param array $attributes Array of key-value pair attributes.
     *      Format: [['attribute' => '...', 'op' => '...', 'value' => '...', 'type' => 'reply/check']]
     */
    public function syncProfileAttributes(Model $profile, array $attributes): void
    {
        $groupname = $this->generateGroupname($profile);

        // Hapus mapping lama
        RadiusGroupsMap::where('profile_type', get_class($profile))
            ->where('profile_id', $profile->id)
            ->delete();

        // Insert mapping baru
        $maps = [];
        foreach ($attributes as $attr) {
            $maps[] = [
                'groupname' => $groupname,
                'attribute' => $attr['attribute'],
                'op' => $attr['op'] ?? '=',
                'value' => $attr['value'],
                'type' => $attr['type'] ?? 'reply',
                'profile_type' => get_class($profile),
                'profile_id' => $profile->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (count($maps) > 0) {
            RadiusGroupsMap::insert($maps);
        }
    }

    /**
     * Format penamaan unik untuk group di FreeRADIUS berdasarkan tipe profil.
     */
    protected function generateGroupname(Model $profile): string
    {
        $classBase = class_basename($profile); // Misal: HotspotProfile
        return strtolower($classBase) . '_' . $profile->id;
    }
}
