<?php

use Illuminate\Database\Seeder;
use App\Models\Info;
use App\Tools;

class InfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $init_file = json_decode(file_get_contents(base_path() . "/init.json", true));

        Info::create([
            'name'              => "timezone",
            'value'             => $init_file->timezone
        ]);

        Info::create([
            'name'              => "apartment_localization",
            'value'             => json_encode($init_file->apartment_localization),
        ]);

    }
}
