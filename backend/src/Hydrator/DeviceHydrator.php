<?php
namespace Backend\Hydrator;

use Backend\Entity\Device;

class DeviceHydrator {
    public static function hydrate(array $data): Device {
        $device = new Device();
        $device->id = $data['id'];
        $device->nazov_zariadenia = $data['nazov_zariadenia'];
        $device->author_id = $data['author_id'];
        return $device;
    }
}