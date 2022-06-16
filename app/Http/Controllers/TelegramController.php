<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeRequest;
use danog\MadelineProto\API;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TelegramController extends Controller
{
    public function start(CodeRequest $request = null)
    {
        $code = $request ? $request->validated() : null;

        if(file_exists( env('TELEGRAM_SESSION_FILE') ) ) {
            $madeline = new API( env('TELEGRAM_SESSION_FILE') );
            if ($code){
                $madeline->complete_phone_login($code);
            }
        } else {
            // Иначе создать новую сессию
            $madeline = new API(env('TELEGRAM_SESSION_FILE'), [
                'app_info' => [
                    'api_id' => env('TELEGRAM_API_ID'),
                    'api_hash' => env('TELEGRAM_API_HASH'),
                ]
            ]);

            // Задать имя сессии
//            $madeline->session = env('TELEGRAM_SESSION_FILE');

            // Принудительно сохранить сессию
            $madeline->serialize();

            // Начать авторизацию по номеру мобильного телефона
            $madeline->phone_login( env('TELEGRAM_PHONE') );
            // Запросить код с помощью консоли
//            $code = readline('Enter the code you received: ');
//            $madeline->complete_phone_login($code);
        }

//        $messages = $madeline->messages->getHistory(['peer' => '@ANY_CHANNEL_ID', 'offset_id' => 0, 'offset_date' => 0, 'add_offset' => 0, 'limit' => 10, 'max_id' => 0, 'min_id' => 0, 'hash' => 0, ]);

//        foreach($messages['messages'] as $msg) {
//            dump($msg);
//        }
        return ['ststus' => 'Ok', 'message' => 'Вы авторизованы', 'errors' => 0];
    }

    public function searchContact()
    {
        $madeline = new API( env('TELEGRAM_SESSION_FILE') );
//        $inputPhoneContact = [
//            '_' => 'inputPhoneContact',
//            'client_id' => 1,
//            'phone' => '+79234805398',
//            'first_name' => 'String',
//            'last_name' => 'String'];
//        $updates = $madeline->contacts->importContacts([$inputPhoneContact]);
//        $updates = $madeline->contacts->search(['q'=>'79537397266', 'limit'=>10]);
//
//        return $updates;

        return $madeline->getSelf();
    }

    public function logout()
    {
        $madeline = new API( env('TELEGRAM_SESSION_FILE') );
        $madeline->logout();
//        $madeline->shutdown();
        $madeline->stop();

        array_map('unlink', glob(env('TELEGRAM_SESSION_FILE')."*"));

//        $legacySessionPath = app_path("../".env('TELEGRAM_SESSION_FILE'));
//        $ipcCallbackPath = app_path("../".env('TELEGRAM_SESSION_FILE').".callback.ipc");
//        $ipcPath = app_path("../".env('TELEGRAM_SESSION_FILE').".ipc");
//        $ipcStatePath = app_path("../".env('TELEGRAM_SESSION_FILE').".ipcState.php");
//        $ipcStateLockPath = app_path("../".env('TELEGRAM_SESSION_FILE').".ipcState.php.lock");
//        $lightStatePath = app_path("../".env('TELEGRAM_SESSION_FILE').".lightState.php");
//        $lightStateLockPath = app_path("../".env('TELEGRAM_SESSION_FILE').".lightState.php.lock");
//        $lockPath = app_path("../".env('TELEGRAM_SESSION_FILE').".lock");
//        $sessionPath = app_path("../".env('TELEGRAM_SESSION_FILE').".safe.php");
//        $safeLockPath = app_path("../".env('TELEGRAM_SESSION_FILE').".safe.php.lock");

//        if (File::delete([
//            $legacySessionPath,
//            $ipcCallbackPath,
//            $ipcPath,
//            $ipcStatePath,
//            $ipcStateLockPath,
//            $lightStatePath,
//            $lightStateLockPath,
//            $lockPath,
//            $sessionPath,
//            $safeLockPath,
//        ])) {
//            return 'Вы вышли';
//        }
        return 'Вы вышли';
    }

    public function me()
    {
        $madeline = new API(env('TELEGRAM_SESSION_FILE'));

        $me = $madeline->getSelf();

//        foreach ($me as $key=>$value) {
//            if (!is_array($value)) {
//                echo $key . '=>' . $value;
//            } else {
//                foreach ($value as $k=>$v) {
//                    echo $k . '=>' . $v;
//                }
//            }
//        }
        var_dump($me);
    }
}
