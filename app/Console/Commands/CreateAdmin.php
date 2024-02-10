<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserCredential;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@entryqc.id',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('Admin');
        UserCredential::create([
            'user_id' => $admin->id,
            'phone_number' => '0',
        ]);
        $this->info('Admin created successfully');
    }
}
