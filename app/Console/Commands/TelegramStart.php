<?php

namespace App\Console\Commands;

use danog\MadelineProto\API;
use Illuminate\Console\Command;

class TelegramStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Авторизация в клиенте';

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
                    'api_id' => env('TELEGRAM_API_ID'),
                    'api_hash' => env('TELEGRAM_API_HASH'),
                ]
            ]);

            // Принудительно сохранить сессию
            $madeline->serialize();

            // Начать авторизацию по номеру мобильного телефона
            $madeline->phone_login( env('TELEGRAM_PHONE') );

            // Запросить код с помощью консоли
            $code = readline('Введите полученный код: ');

            $madeline->complete_phone_login($code);
        }

    }
}
