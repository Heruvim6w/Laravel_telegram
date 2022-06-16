<?php

namespace App\Console\Commands;

use danog\MadelineProto\API;
use Illuminate\Console\Command;

class TelegramLogout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:logout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Выход из клиента';

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
        $this->info('Logout');
        $madeline = new API( env('TELEGRAM_SESSION_FILE') );

        $madeline->logout();

        $this->info('Logout was successfully');
        $this->info('Shutdown');

        $madeline->stop();

        array_map('unlink', glob(env('TELEGRAM_SESSION_FILE')."*"));
        $this->info('Shutdown was successfully');
    }
}
