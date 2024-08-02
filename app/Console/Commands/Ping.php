<?php

namespace App\Console\Commands;

use App\Events\Pinged;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\text;

class Ping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ping {--message=}';

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
        $interactive = !$this->option('no-interaction');
        $message = $this->option('message');

        $message ??= $interactive ? text(
            label: 'What message should the users receive?',
            placeholder: 'Optional',
        ) : null;

        broadcast(new Pinged(message: $message));
    }
}
