<?php

namespace App\Console\Commands;

use danog\MadelineProto\API;
use Illuminate\Console\Command;

class TelegramMe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:me';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Вывод личной информации';

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
        $this->info('Start');
        $madeline = new API( env('TELEGRAM_SESSION_FILE') );

        $me = $madeline->getSelf();

        foreach ($me as $key=>$value) {
            if (!is_array($value)) {
                $this->info($key . '=>' . $value);
            } else {
                foreach ($value as $k=>$v) {
                    $this->info($k . '=>' . $v);
                }
            }
        }

        $this->info('OK!');
    }
}
