<?php

namespace Revys\RevyAdmin\Database\Seeds;

use Illuminate\Database\Seeder;
use Revys\RevyAdmin\App\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'login' => config('admin.auth.login'),
            'email' => config('admin.auth.email'),
            'password' => \Hash::make(config('admin.auth.password')),
            'admin' => true
        ]);
    }
}