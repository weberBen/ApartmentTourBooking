<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Tools;
use App\Models\UserLoginUrl;

class UserLoginUrlsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach($users as $user)
        {
            UserLoginUrl::create([
                'id_user' => $user->id,
                'active'  => false,
            ]);
        }
    }
}
