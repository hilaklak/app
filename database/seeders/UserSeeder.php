<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {

        $user1 = User::create([
            'email' => 'testuser@gmail.com',
            'username' => 'testuser',
            'password' => 'testuser',
            'mobile' => '09128880988',
            'is_admin' => false,
        ]);

        $user1->profile()->update([
            'fname' => 'کاربر',
            'lname' => 'تست',
        ]);

        $user1->metas()->update([
            'email_verified_at' => now(),
        ]);

        $user2 = User::create([
            'email' => 'milad.jafari6210@gmail.com',
            'username' => 'miladimos',
            'mobile' => '09376686365',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $user2->profile()->update([
            'fname' => 'میلاد',
            'lname' => 'جعفری',
            'bio' => 'علاقه مند به برنامه نویسی، قهوه و  چند چیز دیگه...',
        ]);

        $user2->metas()->update([
            'email_verified_at' => now(),
        ]);

        $user2->wallet()->update([
            'amount' => 2000000,
        ]);

        // $user2->roles()->attach([1, 2]);

        $user3 = User::create([
            'email' => 'miladimos@outlook.com',
            'username' => 'miladcoach',
            'mobile' => '09376686366',
            'password' => 'password',
            'is_admin' => false,
        ]);

        $user3->profile()->update([
            'fname' => 'مانی',
            'lname' => 'جعفری',
            'bio' => 'علاقه مند به برنامه نویسی، قهوه و  چند چیز دیگه...',
        ]);

        $user3->metas()->update([
            'email_verified_at' => now(),
        ]);

        $user3->wallet()->update([
            'amount' => 0,
        ]);

        // $user3->roles()->attach([2]);
    }
}
