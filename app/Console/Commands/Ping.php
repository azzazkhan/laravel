<?php

namespace App\Console\Commands;

use App\Events\Pinged;
use App\Models\User;
use Illuminate\Console\Command;

class Ping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pings all users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::all(['id'])->each(fn (User $user) => Pinged::dispatch($user));
    }
}
