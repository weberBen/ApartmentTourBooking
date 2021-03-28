<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Tools;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $init_file = json_decode(file_get_contents(base_path() . "/init.json", true));

        $phone = Tools::phoneToFormat($init_file->lodger->phone);
        User::create([
            'name'              => $init_file->lodger->name,
            'phone'             => $phone,
            'password'          => Hash::make($init_file->lodger->pwd),
            'right_level'       => User::$RIGHT_LEVELS["admin"],
        ]);


        $phone = Tools::phoneToFormat($init_file->owner->phone);
        User::create([
            'name'              => $init_file->owner->name,
            'phone'             => $phone,
            'password'          => Hash::make($init_file->owner->pwd),
            'right_level'       => User::$RIGHT_LEVELS["manager"],
        ]);

    }
}
