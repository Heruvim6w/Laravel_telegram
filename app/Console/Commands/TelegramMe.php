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
    protected $description = 'Command description';

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
//        $me = $madeline->contacts->getContacts(['hash'=>9223372036854775807]);
//        $me = $madeline->contacts->search(['q'=>'Alexandr Kozhevnikov', 'limit'=>10]);
        $inputPhoneContact = [
            '_' => 'inputPhoneContact',
            'client_id' => random_int(120, 200),
            'phone' => '79234805398',
            'first_name' => 'String',
            'last_name' => 'String',
        ];//624874892  1720684456 1984936575

        $this->info('Contact ' . $inputPhoneContact['client_id']);
        $me = $madeline->contacts->importContacts(['contacts' => [$inputPhoneContact]]);
        $this->info('Contact is OK!');
//        $me = $madeline->messages->sendMessage(['peer' => '@d_artagnan_s', 'message' => 'hi']);

//        \danog\MadelineProto\Logger::log("Hi ".$me['first_name']."!");

        foreach ($me['users'] as $value) {
            var_dump($value);
            $this->info($value);
        }
         //else {
//            foreach ($me as $key => $value) {
//                if (!is_array($value)) {
//                    $this->info($key . '= >' . $value);
//                } //else {
//                    foreach ($value as $v) {
//                        $this->info($v);
//                    }
//                }
//            }
//        }
        $this->info('OK!');
//        $c = $madeline->contacts->search(['q'=>'d_artangan_s', 'limit'=>10]);
//        $this->info($c);
//        foreach ($madeline->getAllMethods() as $tt) {
//            $this->info($tt);
//        }
    }
}
