<?php

namespace Database\Seeders;

use App\Models\Device;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #Device::factory(50)->create();
        //$devices = Device::all();

        /*foreach ($devices as $device) {
            $size = 300;
            $filename = 'device_' . $device->id . '.png';
            $storagePath = public_path('devices_qr/' . $filename);

            if (!File::exists(public_path('devices_qr'))) {
                File::makeDirectory(public_path('devices_qr'));
            }

            QrCode::format('png')->size($size)->generate($device->id, $storagePath);
        }*/
    }
}
