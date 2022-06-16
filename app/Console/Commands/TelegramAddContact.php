<?php

namespace App\Console\Commands;

use danog\MadelineProto\API;
use Illuminate\Console\Command;

class TelegramAddContact extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:addContact';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт контакта';

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
        $madeline->start();
        $this->info('Start is Ok!');

        // Запросить данные пользователя с помощью консоли
        $phone = readline('Введите телефон (без +. Например, 79777777777: ');
        $first_name = readline('Введите имя: ');
        $last_name = readline('Введите фамилию (не обязательно): ');

        $inputPhoneContact = [
            '_' => 'inputPhoneContact',
            'client_id' => random_int(120, 200),
            'phone' => $phone,
            'first_name' => $first_name,
            'last_name' => $last_name ?? '',
        ];

        $this->info('Contact ' . $inputPhoneContact['client_id']);
        $updates = $madeline->contacts->importContacts(['contacts' => [$inputPhoneContact]]);
        $this->info('Contact is OK!');

        if (!is_array($updates)) {
            $this->info($updates);
        } else {
            foreach ($updates as $key=>$value) {
                if (!is_array($value)) {
                    $this->info($key . '= >' . $value);
                } else {
                    foreach ($value as $k=>$v) {
                        $this->info($k . ' => ' . $v);
                    }
                }
            }
        }
        $this->info('OK!');
    }
}
