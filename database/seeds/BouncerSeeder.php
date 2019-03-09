<?php

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class BouncerSeeder extends Seeder
{
    public function run()
    {
        $admin = Bouncer::role()->firstOrCreate([
            'name' => 'admin',
            'title' => 'Administrator',
        ]);

        $ban = Bouncer::ability()->firstOrCreate([
            'name' => 'ban-users',
            'title' => 'Ban users',
        ]);

        Bouncer::allow($admin)->to($ban);
    }
}