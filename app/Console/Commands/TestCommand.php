<?php

namespace App\Console\Commands;

use danog\MadelineProto\API;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:echo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Вывод чего-то';

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
        // Если файл с сессией уже существует, использовать его
        if(file_exists( env('TELEGRAM_SESSION_FILE') ) ) {
            $madeline = new API( env('TELEGRAM_SESSION_FILE') );
        } else {
            // Иначе создать новую сессию
            $madeline = new API(env('TELEGRAM_SESSION_FILE'), [
                'app_info' => [
                    'api_id' => '19552121',
                    'api_hash' => 'f84a39d15043c3c5454ab456ad4c42d5',
                ]
            ]);

            // Задать имя сессии
//            $madeline->session = env('TELEGRAM_SESSION_FILE');

            // Принудительно сохранить сессию
            $madeline->serialize();

            // Начать авторизацию по номеру мобильного телефона
            $madeline->phone_login( env('TELEGRAM_PHONE') );
            // Запросить код с помощью консоли
            $code = readline('Enter the code you received: ');
            $madeline->complete_phone_login($code);
        }

        $messages = $madeline->messages->getHistory(['peer' => '@ANY_CHANNEL_ID', 'offset_id' => 0, 'offset_date' => 0, 'add_offset' => 0, 'limit' => 10, 'max_id' => 0, 'min_id' => 0, 'hash' => 0, ]);

        foreach($messages['messages'] as $msg) {
            dump($msg);
        }
    }
}
