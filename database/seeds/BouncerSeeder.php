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

        $moderator = Bouncer::role()->firstOrCreate([
            'name' => 'mod',
            'title' => 'Moderator',
        ]);

        $ban = Bouncer::ability()->firstOrCreate([
            'name' => 'ban-users',
            'title' => 'Ban users',
        ]);

        $delete = Bouncer::ability()->firstOrCreate([
            'name' => 'delete-signatures',
            'title' => 'Delete signatures',
        ]);

        $update = Bouncer::ability()->firstOrCreate([
            'name' => 'update-signatures',
            'title' => 'Update signatures',
        ]);

        Bouncer::allow($admin)->to($ban);
        Bouncer::allow($admin)->to($update);
        Bouncer::allow($admin)->to($delete);

        Bouncer::allow($moderator)->to($update);
    }
}