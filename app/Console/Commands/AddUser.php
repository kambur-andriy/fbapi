<?php

namespace App\Console\Commands;

use App\Models\DB\User;
use App\Models\Processors\Account;
use Illuminate\Console\Command;

class AddUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add admin user to the system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('User email: ');
        $password = $this->ask('User password: ');


        $user = Account::addUser(
            [
                'email' => $email,
                'password' => $password,
            ]
        );

        $user->role = User::USER_ROLE_ADMIN;
        $user->save();

        $this->info('User successfully created.');
    }
}
